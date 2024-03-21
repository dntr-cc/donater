<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class OpenGraphImageService
{
    protected Filesystem $filesystem;
    protected TemporaryDirectory $tmpDir;

    public function __construct()
    {
        $this->filesystem = Storage::disk('opengraph');
        $this->tmpDir = TemporaryDirectory::make();
    }

    public function getUserImage(User $user): string
    {
        $fileName = $this->getOpenGraphImageName($user->getId());
        $image = url('/images/donater.com.ua.png');
        if ($this->filesystem->exists($fileName)) {
            return $this->filesystem->url($fileName);
        }
        try {
            $image = $this->generateOpenGraphImage($user, $fileName);
        } catch (\Throwable $throwable) {
            \Log::critical($throwable->getMessage(), ['tarce' => $throwable->getTraceAsString()]);
        }

        return $image;
    }

    /**
     * @param int $userId
     * @return string
     */
    protected function getOpenGraphImageName(int $userId): string
    {
        return strtr('open-graph-donater-com-ua-user-id-:userId.png', [':userId' => $userId]);
    }

    /**
     * @param User $user
     * @param string $openGraphImageName
     * @return string|null
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    private function generateOpenGraphImage(User $user, string $openGraphImageName): ?string
    {
        $manager = new ImageManager(new Driver());
        $donatTmpPath = $this->tmpDir->path('donut.png');
        $filepathScaledAvatar = $this->tmpDir->path($openGraphImageName);
        $imageTemplate = $manager->read(base_path('public/images/opengraph/template/ava-mask.png'));
        $imageAvatar = $manager->read(base_path('public/' . $user->getAvatar()));
        $scaledAvatar = $imageAvatar->scale(width: 380)->cover(380, 380)->toPng();
        $scaledAvatar->save($filepathScaledAvatar);
        $this->createMaskedAvatar($filepathScaledAvatar);
        $imageScaledAvatar = $manager->read($filepathScaledAvatar);
        $imageTemplate->place($imageScaledAvatar, 'top-left', 49, 20);
        $manager->read(base_path('public/images/opengraph/template/donut.png'))->toPng()->save($donatTmpPath);
        $imageTemplate->place($donatTmpPath, 'top-left', 270, 270);

        $offset = 20;
        if (!empty(trim($user->getFullName()))) {
            $imageUsernamePath = $this->getTextImagePath('Bold', $user->getFullName(), 60, $manager);
            $imageTemplate->place($imageUsernamePath, 'top-right', 50, $offset);
            $offset += 65;
        }
        $imageUsernamePath = $this->getTextImagePath('Regular', '@' . $user->getUsername(), 50, $manager);
        $imageTemplate->place($imageUsernamePath, 'top-right', 50, $offset);
        $offset += 65;

        $fundraisings = $user->getFundraisings();
        $sourcesLines = [
            [
                ['Підписано на волонтерів:', (string)$user->getSubscribersAsSubscriber()->count()],
                ['Зроблено донатів:', $user->getDonateCount() . 'грн.'],
                ['Задоначено через сайт:', $user->getDonatesSumAll() . 'грн.'],
                ['Додано призів:', $user->getPrizesCount() . 'шт.'],
            ],
        ];
        if ($fundraisings->count()) {
            $rows = app(\App\Services\RowCollectionService::class)->getRowCollection($fundraisings);
            $sourcesLines[] = [
                ['Підписалося користувачів:', (string)$user->getSubscribers()->count()],
                ['Всього зборів:', $user->getFundraisings()->count()],
                ['Загалом зібрано коштів:', $rows->allSum() . 'грн.'],
                ['Зібрано від користувачів:', $rows->allSumFromOurDonates() . 'грн.'],
            ];
        }
        foreach ($sourcesLines as $source) {
            foreach ($source as [$left, $right]) {
                $imageLinePath = $this->getTextImagePath('Regular', $right, 25, $manager);
                $imageTemplate->place($imageLinePath, 'top-right', 50, $offset);
                $imageLinePath = $this->getTextImagePath('Regular', $left, 25, $manager);
                $imageTemplate->place($imageLinePath, 'top-left', 500, $offset);
                $offset += 30;
            }
            $offset += 35;
        }

        $encoded = $imageTemplate->toPng();
        $encoded->save(base_path('public/images/opengraph/' . $openGraphImageName));

        return $this->filesystem->url($openGraphImageName);
    }

    /**
     * @param string $fontType
     * @param string $text
     * @param int $fontSize
     * @return string
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    protected function getTextImageSavedPath(string $fontType, string $text, int $fontSize): string
    {
        $imageText = new Imagick();
        $draw = new ImagickDraw();
        $color = new ImagickPixel('#000000');
        $background = new ImagickPixel('none');
        $draw->setFont(base_path('public/fonts/Iskra/IskraCYR-' . $fontType . '.otf'));
        $draw->setFontSize($fontSize);
        $draw->setFillColor($color);
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $metrics = $imageText->queryFontMetrics($draw, $text);
        $draw->annotation(0, $metrics['ascender'], $text);
        $imageText->newImage($metrics['textWidth'], $metrics['textHeight'], $background);
        $imageText->setImageFormat('png');
        $imageText->drawImage($draw);
        $imageUsernamePath = $this->tmpDir->path('text-tmp.png');
        file_put_contents($imageUsernamePath, $imageText);
        return $imageUsernamePath;
    }

    /**
     * @param mixed $filepathScaledAvatar
     * @throws \ImagickException
     */
    protected function createMaskedAvatar(mixed $filepathScaledAvatar): void
    {
        $base = new Imagick($filepathScaledAvatar);
        $mask = new Imagick(base_path('public/images/opengraph/template/avatar-mask.png'));
        $base->compositeImage($mask, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
        $base->writeImage($filepathScaledAvatar);
    }

    /**
     * @param string $fontType
     * @param string $text
     * @param int $fonrSize
     * @param ImageManager $manager
     * @return string
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    protected function getTextImagePath(string $fontType, string $text, int $fonrSize, ImageManager $manager): string
    {
        $imageUsernamePath = $this->getTextImageSavedPath($fontType, $text, $fonrSize);
        $imageTextReady = $manager->read($imageUsernamePath);
        $scaledImageTextReady = $imageTextReady->toPng();
        $scaledImageTextReady->save($imageUsernamePath);

        return $imageUsernamePath;
    }
}
