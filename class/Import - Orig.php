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
            $sheet = $spreadsheet->getSheetByName('Sheet2');

            // Check if the required headers exist
            $requiredHeaders = ['Student ID', 'FullName', 'B_date', 'Sex', 'Home_Add', 'Civil Status', 'Semester', 'Grade / Year Level', 'SY'];
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
                        if ($columnIndex === 'C') {
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
                
                foreach ($data as $row) {
                    $studentId = $row[0]; // Assuming Student ID is in the first column

                    // Check if the student exists in the database
                    $query = "SELECT COUNT(*) FROM StudentData WHERE StudentID = :studentId";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':studentId', $studentId);
                    $stmt->execute();
                    $count = $stmt->fetchColumn();

                    if ($studentId !== null) {
                       
                        if ($count > 0) {
                            // Student already exists, perform update
                            $query = "UPDATE StudentData SET FullName = :fullName, Birthday = :bDate, Gender = :sex, Address = :homeAdd, Status = :civilStatus, Semester = :semester, YearLevel = :gradeYearLevel, SchoolYear = :sy WHERE StudentID  = :studentId";
                        } else {
                            // Student doesn't exist, perform insert
                            $query = "INSERT INTO StudentData (StudentID, FullName, Birthday, Gender, Address, Status, Semester, YearLevel, SchoolYear) VALUES (:studentId, :fullName, :bDate, :sex, :homeAdd, :civilStatus, :semester, :gradeYearLevel, :sy)";
                        }

                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':studentId', $studentId);
                        $stmt->bindParam(':fullName', $row[1]); // Assuming FullName is in the second column
                        $stmt->bindParam(':bDate', $row[2]);  // Assuming B_date is in the third column
                        $stmt->bindParam(':sex', $row[3]); // Assuming Sex is in the fourth column
                        $stmt->bindParam(':homeAdd', $row[4]); // Assuming Home_Add is in the fifth column
                        $stmt->bindParam(':civilStatus', $row[5]); // Assuming Civil Status is in the sixth column
                        $stmt->bindParam(':semester', $row[6]); // Assuming Semester is in the seventh column
                        $stmt->bindParam(':gradeYearLevel', $row[7]); // Assuming Grade / Year Level is in the eighth column
                        $stmt->bindParam(':sy', $row[8]); // Assuming SY is in the ninth column
                
                        $stmt->execute();
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
