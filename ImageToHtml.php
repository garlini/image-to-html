<?php

/*
 * Converte uma imagem em html e css.
 */

/**
 * Description of ImageToHtml
 *
 * @author Garlini
 */
class ImageToHtml {
    
    /**
     *
     * @var resource
     */
    private $image;
    
    /**
     *
     * @var int
     */
    private $width;
    
    
    /**
     *
     * @var int
     */
    private $height;

    /**
     * 
     * @param resource $image
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct($image)
     {
        if (!is_resource($image)) {
            throw new InvalidArgumentException('Imagem invalida.');
        }
         $this->image = $image;
         
         $this->width = imagesx($image);
         $this->height = imagesy($image);
         if (false === $this->width || false === $this->height) {
            throw new RuntimeException('Não foi possível determinar o tamanho da imagem.');
         }
     }
     
     /**
      * 
      * @return string
      */
     public function generateCSS()
     {
         $css = '<style> .pixel { float: left; width: 1px; height: 1px; } ';
         
         $colors = array();
         for ($y = 0; $y < $this->height; $y++) {
             for ($x = 0; $x < $this->width; $x++) {
                 $rgb = imagecolorat($this->image, $x, $y);
                 if (!array_key_exists($rgb, $colors)) {
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                 
                    $color = "rgb($r, $g, $b)";
                     
                     $css .= ".color_$rgb { background-color: $color; } ";
                     
                     $colors[$rgb] = $rgb;
                 }
             }
         }
         
         $css .= '</style>';
         
         return $css;
     }
     
     /**
      * 
      * @return string
      */
     public function generateHTML()
     {
         $html = '';
         
         for ($y = 0; $y < $this->height; $y++) {
             $html .= '<div>';
             for ($x = 0; $x < $this->width; $x++) {
                 $rgb = imagecolorat($this->image, $x, $y);
                 
                 $class = "pixel color_$rgb";
                 
                 $html .= "<div class=\"$class\"></div>";
             }
             $html .= '<div style="clear: both;"></div></div>';
         }
         
         return $html;
     }
     
}
