<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';
require_once 'config/jwt.php';
require_once './../assets/utility.php';
require_once 'Database.php';


use PhpOffice\PhpSpreadsheet\IOFactory;

class Import {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function student() {
    
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (isset($_FILES['xlsFile'])) {
        $file = $_FILES['xlsFile'];

        // Validate the file type
        if ($file['type'] === 'application/vnd.ms-excel' || $file['type'] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            // Process the uploaded file
            $filePath = $file['tmp_name'];

            // Load the file using PhpSpreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Check if the required headers exist
            $requiredHeaders = [
                'Student ID',
                'FullName',
                'Enrollement_select::Subject code',
                'Enrollement_select::Description',
                'Enrollement_select::Room name',
                'Enrollement_select::Day',
                'Enrollement_select::Time',
                'Enrollement_select::Units',
                'Enrollement_select::instructor_name',
                'Enrollement_select::amount',
                'B_date',
                'Age',
                'ttl_units',
                'B_place',
                'Sex',
                'Religion',
                'Citizenship',
                'Home_Add',
                'Home_No',
                'Civil Status',
                'Semester',
                'Grade / Year Level',
                'Section',
                'Major',
                'Course',
                'SY',
                'type',
                'Type of Scholarship',
                'Fees Status'
            ];

            $headersRow = $sheet->getRowIterator()->current();
            $headers = [];

            foreach ($headersRow->getCellIterator() as $cell) {
                $headers[] = $cell->getValue();
            }

            if (array_diff($requiredHeaders, $headers) === []) {
                // Headers match, retrieve data from Sheet1
                $dataSheet = $spreadsheet->getSheetByName('Sheet1');
                $data = [];

                foreach ($dataSheet->getRowIterator(2) as $row) {
                    $rowData = [];
                    $error = false; // Flag to track errors in the current row
                    $studentName = ''; // Variable to store the name of the student causing the error
                    foreach ($row->getCellIterator() as $cell) {
                        $columnIndex = $cell->getColumn();
                        $cellValue = $cell->getValue();

                        // Convert birthdate column (column L) to desired format
                        if ($columnIndex === 'L') {
                            // Check if the birthdate format is "m-d-Y"
                            if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $cellValue)) {
                                // Reformat the birthdate to "Y-m-d"
                                $birthdateParts = explode('-', $cellValue);
                                $formattedBirthdate = $birthdateParts[2] . '-' . $birthdateParts[0] . '-' . $birthdateParts[1];
                                $rowData[] = $formattedBirthdate;
                            } elseif ($cellValue === "?") {
                                $rowData[] = "0000-00-00";
                            } else {
                                try {
                                    $birthdate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue);
                                    $formattedBirthdate = $birthdate->format('Y-m-d');
                                    $rowData[] = $formattedBirthdate;
                                } catch (PhpSpreadsheetException $e) {
                                    $error = true; // Set the error flag if conversion fails
                                    $studentName = $rowData[1] ?? ''; // Assuming FullName is in the second column
                                    error_log("Error converting birthdate for student: $studentName"); // Log the error
                                    break; // Move to the next row immediately
                                }
                            }
                        } else {
                            $rowData[] = $cellValue;
                        }
                    }

                    //if (!$error) {
                        $data[] = $rowData;
                    //}
                }


                // Further processing of the data
                $pdo = $this->conn; // Assuming $this->conn is a PDO instance
                $studentID = null;
                
                foreach ($data as $row) {
                    
                    // If Row is not empty, then store it in StudentID.
                    if($row[0] !== null){
                        $studentID = $row[0];
                    }

                    // Check if the student exists in the database
                    $query = "SELECT COUNT(*) FROM StudentData WHERE StudentID = :studentId";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':studentId', $studentID);
                    $stmt->execute();
                    $count = $stmt->fetchColumn();

                    if ($row[3] === null || $row[4] === null || $row[5] === null || $row[6] === null || $row[7] === null) {
                        break; // Stop importing further data if any of the required columns is null
                    }

                    $subjectCode = $row[3];
                    $description = $row[4];
                    $day = $row[6];
                    $time = $row[7];
                    $units = $row[8];
                    $instructorName = $row[9];
                    
                    // FIXING HUMAN ERRORS WITH AUTOMATION
                    $utility = new Utility();

                    $AI_day = $utility->fixDay($day);

                    if($time != 'TBA'){

                        if (strpos($time, ',') !== false) {
                            $parts = explode(',', $time);
                            $firstPart = trim($parts[0]);
                            $secondPart = trim($parts[1]);
                            
                            $AI_military_time = '';
                            $AI_civilian_time = '';

                            $count = count($parts);
                            $index = 0;
                        
                            foreach($parts as $part){
                                
                                $fixTime = $utility->fix_time_format($part);
                                $civilian_start_time = $fixTime['start_time'];
                                $civilian_end_time = $fixTime['end_time'];

                                $military_start_time = $utility->convertToMilitaryTime($fixTime['start_time']);
                                $military_end_time = $utility->convertToMilitaryTime($fixTime['end_time']);
                                
                                $AI_military_time .= $military_start_time . ' - ' . $military_end_time;
                                $AI_civilian_time .= $civilian_start_time . ' - ' . $civilian_end_time;
                                
                                // Add comma and space if it's not the last iteration
                                if ($index !== $count - 1) {
                                    $AI_military_time .= ', ';
                                    $AI_civilian_time .= ', ';
                                }
                                
                                $index++;
                            }

                        } else {
                            $fixTime = $utility->fix_time_format($time);
                            $civilian_start_time = $fixTime['start_time'];
                            $civilian_end_time = $fixTime['end_time'];

                            $military_start_time = $utility->convertToMilitaryTime($fixTime['start_time']);
                            $military_end_time = $utility->convertToMilitaryTime($fixTime['end_time']);
                            
                            $AI_military_time = $military_start_time.' - '.$military_end_time;
                            $AI_civilian_time = $civilian_start_time.' - '.$civilian_end_time;
                        }
                    } else {
                        $AI_military_time = null;
                        $AI_civilian_time = null;
                    }

                    if ($row[0] !== null) {
                       
                        if ($count > 0) {
                            // Student already exists, perform update
                            $query = "UPDATE StudentData SET FullName = :fullName, Birthday = :bDate, Gender = :sex, Address = :homeAdd, Status = :civilStatus, Semester = :semester, YearLevel = :gradeYearLevel, SchoolYear = :sy, Section = :section, Major = :major, Course = :course, Scholarship = :scholarship  WHERE StudentID  = :studentId";
                        } else {
                            // Student doesn't exist, perform insert
                            $query = "INSERT INTO StudentData (StudentID, FullName, Birthday, Gender, Address, Status, Semester, YearLevel, SchoolYear, Section, Major, Course, Scholarship) VALUES (:studentId, :fullName, :bDate, :sex, :homeAdd, :civilStatus, :semester, :gradeYearLevel, :sy, :section, :major, :course, :scholarship)";
                        }

                        // Fix course names
                        $course = strtoupper($row[45]); // Assuming Course is in the ninth column
                        if ($course === 'BSED-ENG') {
                            $course = 'BSED';
                        } elseif ($course === 'BSED-MATH') {
                            $course = 'BSED';
                        } elseif ($course === 'BS CRIM') {
                            $course = 'BSCRIM';
                        }

                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':studentId', $studentID);
                        $stmt->bindParam(':fullName', $row[1]); // Assuming FullName is in the second column
                        $stmt->bindParam(':bDate', $row[11]);  // Assuming B_date is in the third column
                        $stmt->bindParam(':sex', $row[15]); // Assuming Sex is in the fourth column
                        $stmt->bindParam(':homeAdd', $row[18]); // Assuming Home_Add is in the fifth column
                        $stmt->bindParam(':civilStatus', $row[39]); // Assuming Civil Status is in the sixth column
                        $stmt->bindParam(':semester', $row[41]); // Assuming Semester is in the seventh column
                        $stmt->bindParam(':gradeYearLevel', $row[42]); // Assuming Grade / Year Level is in the eighth column
                        $stmt->bindParam(':sy', $row[46]); // Assuming SY is in the ninth column
                        $stmt->bindParam(':section', $row[43]); // Assuming Section is in the ninth column
                        $stmt->bindParam(':major', $row[44]); // Assuming Major is in the ninth column
                        $stmt->bindParam(':course', $course); // Assuming Course is in the ninth column
                        $stmt->bindParam(':scholarship', $row[48]); // Assuming Scholarship is in the ninth column
                
                        $stmt->execute();


                        // Flow of the subjects
                        // Issue Prediction: When the StudentSubject became a foreign key in the future, this will cause error.
                        //
                        // 1. Check if student already have subjects enrolled.
                        //    a. If naa, then delete all.
                        // 2. Check if the portal has a copy of the subject.
                        //    a. If wala, then create a copy. Then recheck balik ug kwaon ang ID.
                        //    b. If naa, then update
                        // 3. Check if student already have this subjects.
                        //    a. If wala, then enroll


                        // 1. Check if student already have subjects enrolled.
                        $query_subject = "SELECT COUNT(*) FROM StudentSubject WHERE StudentData_id = :studentId";
                        $stmt_subject = $pdo->prepare($query_subject);
                        $stmt_subject->bindParam(':studentId', $studentID);
                        $stmt_subject->execute();
                        $count_subject = $stmt_subject->fetchColumn();

                        if ($count_subject > 0) {
                            // a. If naa, then delete all.
                            $deleteQuery = "DELETE FROM StudentSubject WHERE StudentData_id  = :studentId";
                            $deleteStmt = $pdo->prepare($deleteQuery);
                            $deleteStmt->bindParam(':studentId', $studentID);
                            $deleteStmt->execute();
                        } 

                        // 2. Check if the portal has a copy of the subject.
                        $subjectQuery = "SELECT * FROM SubjectData WHERE code = :subjectCode AND description = :description AND day =:day AND time = :time AND unit = :units AND instructor_name = :instructorName";
                        $subjectStmt = $pdo->prepare($subjectQuery);
                        $subjectStmt->bindParam(':subjectCode', $subjectCode);
                        $subjectStmt->bindParam(':description', $description);
                        $subjectStmt->bindParam(':day', $day);
                        $subjectStmt->bindParam(':time', $time);
                        $subjectStmt->bindParam(':units', $units);
                        $subjectStmt->bindParam(':instructorName', $instructorName);
                        $subjectStmt->execute();
                        $subject = $subjectStmt->fetch(PDO::FETCH_ASSOC);

                        if (!$subject) {
                            // a. If wala, then create a copy.
                            $subjectQuery = "INSERT INTO SubjectData (code, description, room_name, day, AI_days, time, AI_military_time, AI_civilian_time, unit, instructor_name, amount) VALUES (:subjectCode, :description, :roomName, :day, :ai_days, :time, :ai_military_time, :ai_civilian_time, :units, :instructorName, :amount)";
                            $subjectStmt = $pdo->prepare($subjectQuery);
                            $subjectStmt->bindParam(':subjectCode', $row[3]);
                            $subjectStmt->bindParam(':description', $row[4]);
                            $subjectStmt->bindParam(':roomName', $row[5]);
                            $subjectStmt->bindParam(':day', $row[6]);
                            $subjectStmt->bindParam(':ai_days', $AI_day);
                            $subjectStmt->bindParam(':time', $row[7]);
                            $subjectStmt->bindParam(':ai_military_time', $AI_military_time);
                            $subjectStmt->bindParam(':ai_civilian_time', $AI_civilian_time);
                            $subjectStmt->bindParam(':units', $row[8]);
                            $subjectStmt->bindParam(':instructorName', $row[9]);
                            $subjectStmt->bindParam(':amount', $row[10]);
                            $subjectStmt->execute();

                            // Then recheck balik ug kwaon ang ID.
                            $subjectQuery = "SELECT * FROM SubjectData WHERE code = :subjectCode AND description = :description AND day =:day AND time = :time AND unit = :units AND instructor_name = :instructorName";
                            $subjectStmt = $pdo->prepare($subjectQuery);
                            $subjectStmt->bindParam(':subjectCode', $subjectCode);
                            $subjectStmt->bindParam(':description', $description);
                            $subjectStmt->bindParam(':day', $day);
                            $subjectStmt->bindParam(':time', $time);
                            $subjectStmt->bindParam(':units', $units);
                            $subjectStmt->bindParam(':instructorName', $instructorName);
                            $subjectStmt->execute();
                            $subject = $subjectStmt->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $subjectID = $subject['id'];

                            // b. If naa, then update
                            $subjectQuery = "UPDATE `SubjectData` SET `code`=:subjectCode,`description`=:description,`room_name`=:roomName,`day`=:day,`AI_days`=:ai_days,`time`=:time,`AI_military_time`=:ai_military_time,`AI_civilian_time`=:ai_civilian_time,`unit`=:units,`instructor_name`=:instructorName,`amount`=:amount WHERE id =:subject_id";
                            $subjectStmt = $pdo->prepare($subjectQuery);
                            $subjectStmt->bindParam(':subjectCode', $row[3]);
                            $subjectStmt->bindParam(':description', $row[4]);
                            $subjectStmt->bindParam(':roomName', $row[5]);
                            $subjectStmt->bindParam(':day', $row[6]);
                            $subjectStmt->bindParam(':ai_days', $AI_day);
                            $subjectStmt->bindParam(':time', $row[7]);
                            $subjectStmt->bindParam(':ai_military_time', $AI_military_time);
                            $subjectStmt->bindParam(':ai_civilian_time', $AI_civilian_time);
                            $subjectStmt->bindParam(':units', $row[8]);
                            $subjectStmt->bindParam(':instructorName', $row[9]);
                            $subjectStmt->bindParam(':amount', $row[10]);
                            $subjectStmt->bindParam(':subject_id', $subjectID);
                            $subjectStmt->execute();
                        }

                        // 3. Check if student already have this subjects.
                        $subjectID = $subject['id'];
                        
                        $enrollmentQuery = "SELECT COUNT(*) FROM StudentSubject WHERE StudentData_ID = :studentId AND SubjectData_ID = :subjectId";
                        $enrollmentStmt = $pdo->prepare($enrollmentQuery);
                        $enrollmentStmt->bindParam(':studentId', $studentID);
                        $enrollmentStmt->bindParam(':subjectId', $subjectID);
                        $enrollmentStmt->execute();
                        $enrollmentCount = $enrollmentStmt->fetchColumn();
                        

                        if ($enrollmentCount == 0) {
                            // a. If wala, then enroll
                            $enrollmentInsertQuery = "INSERT INTO StudentSubject (StudentData_id, SubjectData_id) VALUES (:studentId, :subjectId)";
                            $enrollmentInsertStmt = $pdo->prepare($enrollmentInsertQuery);
                            $enrollmentInsertStmt->bindParam(':studentId', $studentID);
                            $enrollmentInsertStmt->bindParam(':subjectId', $subjectID);
                            $enrollmentInsertStmt->execute();
                            
                        }

                    } else {
                        // Flow of the subjects (In this part)
                        // Issue Prediction: When the StudentSubject became a foreign key in the future, this will cause error.
                        //
                        // 1. Check if the portal has a copy of the subject.
                        //    a. If wala, then create a copy. Then recheck balik ug kwaon ang ID.
                        //    b. If naa, then update the copy.
                        // 2. Check if student already have this subjects.
                        //    a. If wala, then enroll
                            
                        // 1. Check if the portal has a copy of the subject.
                        $subjectQuery = "SELECT * FROM SubjectData WHERE code = :subjectCode AND description = :description AND day =:day AND time = :time AND unit = :units AND instructor_name = :instructorName";
                        $subjectStmt = $pdo->prepare($subjectQuery);
                        $subjectStmt->bindParam(':subjectCode', $subjectCode);
                        $subjectStmt->bindParam(':description', $description);
                        $subjectStmt->bindParam(':day', $day);
                        $subjectStmt->bindParam(':time', $time);
                        $subjectStmt->bindParam(':units', $units);
                        $subjectStmt->bindParam(':instructorName', $instructorName);
                        $subjectStmt->execute();
                        $subject = $subjectStmt->fetch(PDO::FETCH_ASSOC);

                        if (!$subject) {
                            // a. If wala, then create a copy.
                            $subjectQuery = "INSERT INTO SubjectData (code, description, room_name, day, AI_days, time, AI_military_time, AI_civilian_time, unit, instructor_name, amount) VALUES (:subjectCode, :description, :roomName, :day, :ai_days, :time, :ai_military_time, :ai_civilian_time, :units, :instructorName, :amount)";
                            $subjectStmt = $pdo->prepare($subjectQuery);
                            $subjectStmt->bindParam(':subjectCode', $row[3]);
                            $subjectStmt->bindParam(':description', $row[4]);
                            $subjectStmt->bindParam(':roomName', $row[5]);
                            $subjectStmt->bindParam(':day', $row[6]);
                            $subjectStmt->bindParam(':ai_days', $AI_day);
                            $subjectStmt->bindParam(':time', $row[7]);
                            $subjectStmt->bindParam(':ai_military_time', $AI_military_time);
                            $subjectStmt->bindParam(':ai_civilian_time', $AI_civilian_time);
                            $subjectStmt->bindParam(':units', $row[8]);
                            $subjectStmt->bindParam(':instructorName', $row[9]);
                            $subjectStmt->bindParam(':amount', $row[10]);
                            $subjectStmt->execute();

                            // b. Then recheck balik ug kwaon ang ID.
                            $subjectQuery = "SELECT * FROM SubjectData WHERE code = :subjectCode AND description = :description AND day =:day AND time = :time AND unit = :units AND instructor_name = :instructorName";
                            $subjectStmt = $pdo->prepare($subjectQuery);
                            $subjectStmt->bindParam(':subjectCode', $subjectCode);
                            $subjectStmt->bindParam(':description', $description);
                            $subjectStmt->bindParam(':day', $day);
                            $subjectStmt->bindParam(':time', $time);
                            $subjectStmt->bindParam(':units', $units);
                            $subjectStmt->bindParam(':instructorName', $instructorName);
                            $subjectStmt->execute();
                            $subject = $subjectStmt->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $subjectID = $subject['id'];

                            // b. If naa, then update
                            $subjectQuery = "UPDATE `SubjectData` SET `code`=:subjectCode,`description`=:description,`room_name`=:roomName,`day`=:day,`AI_days`=:ai_days,`time`=:time,`AI_military_time`=:ai_military_time,`AI_civilian_time`=:ai_civilian_time,`unit`=:units,`instructor_name`=:instructorName,`amount`=:amount WHERE id =:subject_id";
                            $subjectStmt = $pdo->prepare($subjectQuery);
                            $subjectStmt->bindParam(':subjectCode', $row[3]);
                            $subjectStmt->bindParam(':description', $row[4]);
                            $subjectStmt->bindParam(':roomName', $row[5]);
                            $subjectStmt->bindParam(':day', $row[6]);
                            $subjectStmt->bindParam(':ai_days', $AI_day);
                            $subjectStmt->bindParam(':time', $row[7]);
                            $subjectStmt->bindParam(':ai_military_time', $AI_military_time);
                            $subjectStmt->bindParam(':ai_civilian_time', $AI_civilian_time);
                            $subjectStmt->bindParam(':units', $row[8]);
                            $subjectStmt->bindParam(':instructorName', $row[9]);
                            $subjectStmt->bindParam(':amount', $row[10]);
                            $subjectStmt->bindParam(':subject_id', $subjectID);
                            $subjectStmt->execute();
                        }

                        // 2. Check if student already have this subjects.
                        $subjectID = $subject['id'];
                        
                        $enrollmentQuery = "SELECT COUNT(*) FROM StudentSubject WHERE StudentData_ID = :studentId AND SubjectData_ID = :subjectId";
                        $enrollmentStmt = $pdo->prepare($enrollmentQuery);
                        $enrollmentStmt->bindParam(':studentId', $studentID);
                        $enrollmentStmt->bindParam(':subjectId', $subjectID);
                        $enrollmentStmt->execute();
                        $enrollmentCount = $enrollmentStmt->fetchColumn();
                        
                        if ($enrollmentCount == 0) {
                            // a. If wala, then enroll
                            $enrollmentInsertQuery = "INSERT INTO StudentSubject (StudentData_id, SubjectData_id) VALUES (:studentId, :subjectId)";
                            $enrollmentInsertStmt = $pdo->prepare($enrollmentInsertQuery);
                            $enrollmentInsertStmt->bindParam(':studentId', $studentID);
                            $enrollmentInsertStmt->bindParam(':subjectId', $subjectID);
                            $enrollmentInsertStmt->execute();
                        }

                    }
                }
                
                // Respond with success message
                $response = array(
                    'success' => true,
                    'message' => 'File uploaded and validated successfully.',
                    'data' => $data
                );
            } else {
                // Headers don't match
                http_response_code(400);
                $response = array(
                    'success' => false,
                    'message' => 'Invalid file format, headers cannot be found.'
                );
            }
        } else {
            // Invalid file type
            http_response_code(400);
            $response = array(
                'success' => false,
                'message' => 'Invalid file type. Only XLS files are allowed.'
            );
        }
    } else {
        // No file uploaded
        http_response_code(400);
        $response = array(
            'success' => false,
            'message' => 'No file uploaded.'
        );
    }

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    
  }
  
}

?>
