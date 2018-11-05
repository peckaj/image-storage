<?php


namespace Ublaboo\ImageStorage\Utils;

/**
 * Class Image
 * @package Ublaboo\ImageStorage\Utils
 * @author  Jiri Pecka
 */
class Image extends \Nette\Utils\Image
{
	protected static $formats = [self::JPEG => 'jpeg', self::PNG => 'png', self::GIF => 'gif', self::WEBP => 'webp'];

	/** @var resource */
	protected $image;

	/**
	 * Crops image.
	 * @param  mixed  x-offset in pixels or percent
	 * @param  mixed  y-offset in pixels or percent
	 * @param  mixed  width in pixels or percent
	 * @param  mixed  height in pixels or percent
	 * @return static
	 */
	public function crop($left, $top, $width, $height)
	{
		list($r['x'], $r['y'], $r['width'], $r['height'])
			= static::calculateCutout($this->getWidth(), $this->getHeight(), $left, $top, $width, $height);
		// FIX PNG BLACK BACKGROUND
//		if (PHP_VERSION_ID > 50611) { // PHP bug #67447
//			$this->image = imagecrop($this->image, $r);
//		} else {
			$newImage = static::fromBlank($r['width'], $r['height'], self::RGB(0, 0, 0, 127))->getImageResource();
			imagecopy($newImage, $this->getImageResource(), 0, 0, $r['x'], $r['y'], $r['width'], $r['height']);
			$this->image = $newImage;
//		}
		return $this;
	}

}