<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excelFile"])) {
    $fileTmpName = $_FILES["excelFile"]["tmp_name"];
    $fileName = $_FILES["excelFile"]["name"];
    $filePath = "uploads/" . $fileName;

    error_log("Archivo recibido: " . $fileName);

    if (move_uploaded_file($fileTmpName, $filePath)) {
        error_log("Archivo subido correctamente a: " . $filePath);
        processExcel($filePath);
    } else {
        error_log("Error al mover el archivo: " . $_FILES["excelFile"]["error"]);
        echo "Error al subir el archivo.";
    }
}

function processExcel($filePath) {
    error_log("Inicio del procesamiento del archivo Excel: " . $filePath);

    try {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $row) {
            $CodigoCurso = $row[0];
            $NombreCurso = $row[1];
            $Matriculados = (int) $row[2];
            $Aprobados = (int) $row[3];
            $Desaprobados = (int) $row[4];
            $Retiros = (int) $row[5];
            $Abandonos = (int) $row[6];
            $TotalLlevados = (int) $row[7];
            $CantidadConvalidados = (int) $row[8];
            $NotaMinima = (float) $row[9];
            $NotaMaxima = (float) $row[10];
            $Promedio = (float) $row[11];
            $DesviacionEstandar = (float) $row[12];
            $Semestre = $row[13];

            insertDataIntoDatabase($CodigoCurso, $NombreCurso, $Matriculados, $Aprobados, $Desaprobados, $Retiros, $Abandonos, $TotalLlevados, $CantidadConvalidados, $NotaMinima, $NotaMaxima, $Promedio, $DesviacionEstandar, $Semestre);
        }
    } catch (Exception $e) {
        error_log("Error al procesar el archivo Excel: " . $e->getMessage());
        echo "Error al procesar el archivo Excel.";
    }
}

function insertDataIntoDatabase($CodigoCurso, $NombreCurso, $Matriculados, $Aprobados, $Desaprobados, $Retiros, $Abandonos, $TotalLlevados, $CantidadConvalidados, $NotaMinima, $NotaMaxima, $Promedio, $DesviacionEstandar, $Semestre) {
    $serverName = "bi-segunda-unidad.database.windows.net";
    $database = "CICLO_UNIVERSITARIO";
    $username = "adminsql";
    $password = "Upt2024!";

    $dsn = "sqlsrv:Server=$serverName;Database=$database";

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO Cursos (CodigoCurso, NombreCurso, Matriculados, Aprobados, Desaprobados, Retiros, Abandonos, TotalLlevados, CantidadConvalidados, NotaMinima, NotaMaxima, Promedio, DesviacionEstandar, Semestre)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            $CodigoCurso, $NombreCurso, $Matriculados, $Aprobados, $Desaprobados, 
            $Retiros, $Abandonos, $TotalLlevados, $CantidadConvalidados, 
            $NotaMinima, $NotaMaxima, $Promedio, $DesviacionEstandar, $Semestre
        ]);

        echo "Datos insertados correctamente.";

    } catch (PDOException $e) {
        error_log("Error al conectar o ejecutar la consulta: " . $e->getMessage());
        echo "Error al insertar los datos: " . $e->getMessage();
    }
}
?>
