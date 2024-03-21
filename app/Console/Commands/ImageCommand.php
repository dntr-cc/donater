<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use Illuminate\Console\Command;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\ImageManager;

class ImageCommand extends Command
{
    protected $signature = 'image';

    protected $description = 'Command description';

    public function handle(): void
    {
        $manager = new ImageManager(
            new \Intervention\Image\Drivers\Imagick\Driver()
        );
        foreach (Fundraising::active() as $fundraising) {
            $imageTemplate = $manager->read('public/images/meta/template.png');
            $imageAvatar = $manager->read('public/' . $fundraising->getAvatar());
            $imageAvatar->resize(550);
            $imageTemplate->place($imageAvatar, 'top-left', 40, 25);
            $text = $fundraising->getName();
            $imageText = new Imagick();
            $draw = new ImagickDraw();
            $color = new ImagickPixel('#000000');
            $background = new ImagickPixel('none'); // Transparent
            $draw->setFont('public/fonts/Inter/Inter-Black.ttf');
            $draw->setFontSize(50);
            $draw->setFillColor($color);
            $draw->setStrokeAntialias(true);
            $draw->setTextAntialias(true);
            $metrics = $imageText->queryFontMetrics($draw, $text);
            $draw->annotation(0, $metrics['ascender'], $text);
            $imageText->newImage($metrics['textWidth'], $metrics['textHeight'], $background);
            $imageText->setImageFormat('png');
            $imageText->drawImage($draw);
            file_put_contents('/tmp/text.png', $imageText);
            $imageTemplate->place('/tmp/text.png', 'top-left', 680, 25);
            $encoded = $imageTemplate->toPng();
            $encoded->save('public/images/meta/' . $fundraising->getKey() . '.png');
        }





    }
}
