<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kolom dari tabel <b><?=$this->uri->segment(4);?></b></title>
</head>
<body>
    <h1>Kolom dari tabel <b><?=$this->uri->segment(4);?></b></h1>
    <table border=1>
        <tr>
         <td>Kolom</td>
        </tr>
        <?php
            foreach ($field as $v) {
                echo "
                    <tr>
                        <td> ".$v." </td> 
                    </tr>
                ";
            }
        ?>
    </table>
    <p>Data Post</p>
    $data = [
        <?php foreach ($field as $v) { 
        echo '"'.$v.'" => $this->input->post("'.$v.'"),<br>';
        } ?>];
<hr>
<p>Data Get</p>
    $data = [
        <?php foreach ($field as $v) { 
        echo '"'.$v.'" => $this->input->get("'.$v.'"),<br>';
        } ?>];

<hr>
<p>Array</p>
    $data = [
        <?php foreach ($field as $v) { 
        echo "'".$v."',";
        } ?>];

</body>
</html>