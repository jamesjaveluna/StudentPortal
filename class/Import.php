<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';
require_once 'config/jwt.php';
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
                    foreach ($row->getCellIterator() as $cell) {
                        $columnIndex = $cell->getColumn();
                        $cellValue = $cell->getValue();
                        
                        // Convert birthdate column (column C) to desired format
                        if ($columnIndex === 'L') {
                            $birthdate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue);
                            $formattedBirthdate = $birthdate->format('Y-m-d');
                            $rowData[] = $formattedBirthdate;
                        } else {
                            $rowData[] = $cellValue;
                        }
                    }
                    $data[] = $rowData;
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

                    if ($row[0] !== null) {
                       
                        if ($count > 0) {
                            // Student already exists, perform update
                            $query = "UPDATE StudentData SET FullName = :fullName, Birthday = :bDate, Gender = :sex, Address = :homeAdd, Status = :civilStatus, Semester = :semester, YearLevel = :gradeYearLevel, SchoolYear = :sy, Section = :section, Major = :major, Course = :course, Scholarship = :scholarship  WHERE StudentID  = :studentId";
                        } else {
                            // Student doesn't exist, perform insert
                            $query = "INSERT INTO StudentData (StudentID, FullName, Birthday, Gender, Address, Status, Semester, YearLevel, SchoolYear, Section, Major, Course, Scholarship) VALUES (:studentId, :fullName, :bDate, :sex, :homeAdd, :civilStatus, :semester, :gradeYearLevel, :sy, :section, :major, :course, :scholarship)";
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
                        $stmt->bindParam(':course', $row[45]); // Assuming Course is in the ninth column
                        $stmt->bindParam(':scholarship', $row[48]); // Assuming Scholarship is in the ninth column
                
                        $stmt->execute();

                        // Check if the student exists in the database
                        $query_subject = "SELECT COUNT(*) FROM SubjectData WHERE student_id = :studentId";
                        $stmt_subject = $pdo->prepare($query_subject);
                        $stmt_subject->bindParam(':studentId', $studentID);
                        $stmt_subject->execute();
                        $count_subject = $stmt_subject->fetchColumn();

                        if ($count_subject > 0) {
                            // Student already exists, perform update
                            $deleteQuery = "DELETE FROM SubjectData WHERE student_id = :studentId";
                            $deleteStmt = $pdo->prepare($deleteQuery);
                            $deleteStmt->bindParam(':studentId', $studentID);
                            $deleteStmt->execute();
                        } 

                        $subjectQuery = "INSERT INTO SubjectData (student_id, code, description, room_name, day, time, unit, instructor_name, amount) VALUES (:studentId, :subjectCode, :description, :roomName, :day, :time, :units, :instructorName, :amount)";
                        $subjectStmt = $pdo->prepare($subjectQuery);
                        $subjectStmt->bindParam(':studentId', $studentID);
                        $subjectStmt->bindParam(':subjectCode', $row[3]);
                        $subjectStmt->bindParam(':description', $row[4]);
                        $subjectStmt->bindParam(':roomName', $row[5]);
                        $subjectStmt->bindParam(':day', $row[6]);
                        $subjectStmt->bindParam(':time', $row[7]);
                        $subjectStmt->bindParam(':units', $row[8]);
                        $subjectStmt->bindParam(':instructorName', $row[9]);
                        $subjectStmt->bindParam(':amount', $row[10]);

                        $subjectStmt->execute();

                    } else {
                        // Check if the student exists in the database
                        $query_subject = "SELECT COUNT(*) FROM SubjectData WHERE student_id = :studentId";
                        $stmt_subject = $pdo->prepare($query_subject);
                        $stmt_subject->bindParam(':studentId', $studentID);
                        $stmt_subject->execute();
                        $count_subject = $stmt_subject->fetchColumn();

                        //if ($count_subject > 0) {
                        //    // Student already exists, perform update
                        //    $deleteQuery = "DELETE FROM SubjectData WHERE student_id = :studentId";
                        //    $deleteStmt = $pdo->prepare($deleteQuery);
                        //    $deleteStmt->bindParam(':studentId', $studentID);
                        //    $deleteStmt->execute();
                        //} 

                        $subjectQuery = "INSERT INTO SubjectData (student_id, code, description, room_name, day, time, unit, instructor_name, amount) VALUES (:studentId, :subjectCode, :description, :roomName, :day, :time, :units, :instructorName, :amount)";
                        $subjectStmt = $pdo->prepare($subjectQuery);
                        $subjectStmt->bindParam(':studentId', $studentID);
                        $subjectStmt->bindParam(':subjectCode', $row[3]);
                        $subjectStmt->bindParam(':description', $row[4]);
                        $subjectStmt->bindParam(':roomName', $row[5]);
                        $subjectStmt->bindParam(':day', $row[6]);
                        $subjectStmt->bindParam(':time', $row[7]);
                        $subjectStmt->bindParam(':units', $row[8]);
                        $subjectStmt->bindParam(':instructorName', $row[9]);
                        $subjectStmt->bindParam(':amount', $row[10]);

                        $subjectStmt->execute();
                    }
                }
                
                // Respond with success message
                $response = array(
                    'success' => true,
                    'message' => 'File uploaded and validated successfully.',
                    'data' => $data
                );


                // Respond with success message
                $response = array(
                    'success' => true,
                    'message' => 'File uploaded and validated successfully.',
                    'data' => $data
                );
            } else {
                // Headers don't match
                $response = array(
                    'success' => false,
                    'message' => 'Invalid file format. Sheet2 should contain the required headers.'
                );
            }
        } else {
            // Invalid file type
            $response = array(
                'success' => false,
                'message' => 'Invalid file type. Only XLS files are allowed.'
            );
        }
    } else {
        // No file uploaded
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
