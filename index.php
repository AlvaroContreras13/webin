<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir archivo Excel</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        h1 {
            font-size: 2.5em;
            margin-top: 30px;
            margin-bottom: 50px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }
        form {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
        }
        label {
            font-size: 1.2em;
            margin-bottom: 15px;
            display: block;
        }
        input[type="file"] {
            margin: 15px 0;
            font-size: 1em;
            padding: 10px;
            border-radius: 10px;
            border: none;
            background: #fff;
            color: #333;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background: #d3a1f0;
            border: none;
            color: #fff;
            padding: 10px 15px;
            font-size: 1.2em;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 20px;
            box-sizing: border-box;
        }
        input[type="submit"]:hover {
            background: #c48fe0;
        }
        input[type="file"]:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Subir archivo Excel</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="excelFile">Selecciona un archivo Excel:</label>
        <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx" required>
        <input type="submit" value="Subir archivo">
    </form>
</body>
</html>
