<?php
$servername= "localhost";
$username= "blog_user";
$password= "your_password";
$dbname = "school_db";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
     // Handle GET requests
    case 'GET':
        $stmt = $conn->prepare("SELECT id, name, age, grade FROM students");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = json_encode($data);
        break;
    // Handle POST requests
    case 'POST':
        $data= json_decode(file_get_contents('php:??input'), true);
        $name = $data['name'];
        $age = $data['age'];
        $grade = $data['grade'];
        $stmt = $conn->prepare("INSERT INTO students (name, age, grade) VALUES (:name, :age, :grade)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':grade', $grade);
        $stmt->execute();
        $response = "Record created successfully.";
        break;
    // Handle PUT requests
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $studentId = $data['id'];
        $newAge = $data['age'];
        $stmt = $conn->prepare("UPDATE students SET age = :age WHERE id = :id");
        $stmt->bindParam(':age', $newAge);
        $stmt->bindParam(':id', $studentId);
        $stmt->execute();
        $response = "Record updated successfully.";
        break;
    // Handle DELETE requests
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $studentId = $data['id'];
        $stmt = $conn->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindParam(':id', $studentId);
        $stmt->execute();
        $response = "Record deleted successfully.";
        break;
}
header("Content-Type: application/json");
echo $response;
$conn = null;
?>