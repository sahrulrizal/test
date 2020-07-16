<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tabel</title>
</head>
<body>
    <table border=1>
        <tr>
         <td>Nama Tabel</td>
        </tr>
        <?php
        
        foreach ($tabel as $v) {
            echo "
                <tr>
                    <td><a href='".site_url('lab/tabel/getKolom/'.$v)."' > ".$v." </a></td> 
                </tr>
            ";
        }

        ?>
    </table>
</body>
</html>