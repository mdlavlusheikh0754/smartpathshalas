<?php

namespace App\Helpers;

class PdfCompressor
{
    /**
     * Compress PDF file using Ghostscript
     * 
     * @param string $inputPath
     * @param string $outputPath
     * @return bool
     */
    public static function compress($inputPath, $outputPath)
    {
        // Check if Ghostscript is available
        if (!self::isGhostscriptAvailable()) {
            // If Ghostscript not available, just copy the file
            copy($inputPath, $outputPath);
            return false;
        }

        // Ghostscript command for PDF compression
        $command = sprintf(
            'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s',
            escapeshellarg($outputPath),
            escapeshellarg($inputPath)
        );

        exec($command, $output, $returnCode);

        return $returnCode === 0;
    }

    /**
     * Check if Ghostscript is available
     * 
     * @return bool
     */
    private static function isGhostscriptAvailable()
    {
        exec('gs --version 2>&1', $output, $returnCode);
        return $returnCode === 0;
    }

    /**
     * Get compression ratio
     * 
     * @param string $originalPath
     * @param string $compressedPath
     * @return float
     */
    public static function getCompressionRatio($originalPath, $compressedPath)
    {
        $originalSize = filesize($originalPath);
        $compressedSize = filesize($compressedPath);

        if ($originalSize == 0) {
            return 0;
        }

        return (1 - ($compressedSize / $originalSize)) * 100;
    }
}

