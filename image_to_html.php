<?php 
ini_set('memory_limit', -1);
set_time_limit(10 * 3600);

require './ImageToHtml.php';

$strErro = '';
$generator = null;
$css = '';
$html = '';

if (isset($_FILES['image'])) {
    if (UPLOAD_ERR_OK === $_FILES['image']['error']) {
        $fileContents = file_get_contents($_FILES['image']['tmp_name']);
        if (false !== $fileContents) {
            $image = imagecreatefromstring($fileContents);
            if (false !== $image) {
                $inicio = microtime(true);
                $generator = new ImageToHtml($image);
                $css = $generator->generateCSS();
                $html = $generator->generateHTML();
                $fim = microtime(true);
            } else {
                $strErro = 'Arquivo inválido.';
            }
        } else {
            $strErro = 'Não foi possivel abrir o arquivo.';
        }
    } else {
        $strErro = 'Erro no upload.';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Language" content="pt-br" />
        <title>Image to HTML</title>
        <style>
            .erro { color: red; }
        </style>
        <?php if ($generator):  ?>
            <?php echo $css  ?>
        <?php endif ?>
    </head>
    <body>
    <?php if ($generator):  ?>
        <div class="image"><?php echo $html ?></div>
    <?php endif ?>
        <form method="POST" action="" enctype="multipart/form-data">
        <?php if (!empty($strErro)):  ?>
            <p class="erro"><?php echo htmlspecialchars($strErro) ?></p>
        <?php endif  ?>
            <div>
                <label>Imagem: <input type="file" name="image" required ></label>
            </div>
            <div>
                <input type="submit" value="Enviar">
            </div>
        </form>
    <?php if ($generator):  ?>
        <p>Pico memória: <?php echo number_format(memory_get_peak_usage(), 0, ',', '.') ?></p>
        <p>Tempo: <?php echo number_format($fim - $inicio, 3, ',', '.') ?></p>
    <?php endif ?>
    </body>
</html>
