<?php

namespace App\Utils;

use Exception;

class Base64ImageDecoder
{
    private $base64String;
    private $allowedFormats;
    private $decodedContent;
    private $format;

    public function __construct($base64String, $allowedFormats = ['jpeg', 'png', 'jpg'])
    {
        $this->base64String = $base64String;
        $this->allowedFormats = $allowedFormats;
        $this->decode();
    }

    private function decode()
    {
        // Remove data:image/xxx;base64, prefix if exists
        if (preg_match('/^data:image\/(\w+);base64,/', $this->base64String, $matches)) {
            $this->format = $matches[1];
            $this->base64String = substr($this->base64String, strpos($this->base64String, ',') + 1);
        } else {
            // If no prefix, try to detect format from decoded content
            $this->format = $this->detectFormat();
        }

        // Validate format
        if (!in_array($this->format, $this->allowedFormats)) {
            throw new Exception("Image format {$this->format} is not allowed. Allowed formats: " . implode(', ', $this->allowedFormats));
        }

        // Decode base64 string
        $this->decodedContent = base64_decode($this->base64String);

        if ($this->decodedContent === false) {
            throw new Exception("Failed to decode base64 string");
        }
    }

    private function detectFormat()
    {
        $decoded = base64_decode($this->base64String);

        if ($decoded === false) {
            throw new Exception("Invalid base64 string");
        }

        // Check image signature (magic bytes)
        $signature = substr($decoded, 0, 8);

        // PNG signature
        if (substr($signature, 0, 4) === "\x89PNG") {
            return 'png';
        }

        // JPEG signature
        if (substr($signature, 0, 3) === "\xFF\xD8\xFF") {
            return 'jpeg';
        }

        // Default to jpeg if can't detect
        return 'jpeg';
    }

    public function getDecodedContent()
    {
        return $this->decodedContent;
    }

    public function getFormat()
    {
        return $this->format;
    }
}
