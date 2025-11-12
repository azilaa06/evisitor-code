<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter QR Code Library
 * Wrapper untuk phpqrcode library
 * 
 * @package    CodeIgniter
 * @subpackage Libraries
 * @category   QR Code
 * @author     Your Name
 * @version    3.0
 */
class Ciqrcode {

    protected $CI;
    protected $cachedir;
    protected $temp_dir;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        
        // Setup directories
        $this->cachedir = APPPATH . 'cache/qr/';
        $this->temp_dir = sys_get_temp_dir() . '/';
        
        // Create cache directory if not exists
        if (!is_dir($this->cachedir)) {
            mkdir($this->cachedir, 0755, true);
        }
        
        // Load phpqrcode library
        if (!class_exists('QRcode')) {
            require_once APPPATH . 'third_party/phpqrcode/qrlib.php';
        }
        
        log_message('info', 'Ciqrcode Library Initialized');
    }

    /**
     * Generate QR Code PNG
     * 
     * @param string $data Data yang akan di-encode
     * @param string|null $savename Path file output (null = output langsung ke browser)
     * @param string $level Error correction level: L, M, Q, H (default: H)
     * @param int $size Ukuran modul 1-10 (default: 10)
     * @param int $margin Margin dalam modul (default: 2)
     * @return bool
     */
    public function generate($data, $savename = null, $level = 'H', $size = 10, $margin = 2)
    {
        try {
            // Validate error correction level
            $level = strtoupper($level);
            if (!in_array($level, ['L', 'M', 'Q', 'H'])) {
                $level = 'H';
            }
            
            // Validate size (1-10)
            $size = max(1, min(10, (int)$size));
            
            // Validate margin (0-10)
            $margin = max(0, min(10, (int)$margin));
            
            // Generate QR Code
            QRcode::png($data, $savename, $level, $size, $margin);
            
            log_message('info', 'QR Code generated successfully');
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'QR Code generation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate QR Code ke temporary file dan return path
     * Berguna untuk processing lebih lanjut
     * 
     * @param string $data Data QR
     * @param string $level Error correction
     * @param int $size Ukuran
     * @param int $margin Margin
     * @return string|false Path ke temporary file atau false jika gagal
     */
    public function generate_temp($data, $level = 'H', $size = 10, $margin = 2)
    {
        try {
            $temp_file = $this->temp_dir . 'qr_' . uniqid() . '_' . time() . '.png';
            
            if ($this->generate($data, $temp_file, $level, $size, $margin)) {
                if (file_exists($temp_file)) {
                    return $temp_file;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            log_message('error', 'Failed to generate temp QR: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate QR Code sebagai Base64 string
     * Berguna untuk embed di HTML
     * 
     * @param string $data Data QR
     * @param string $level Error correction
     * @param int $size Ukuran
     * @param int $margin Margin
     * @return string Base64 data URI atau empty string jika gagal
     */
    public function generate_base64($data, $level = 'H', $size = 10, $margin = 2)
    {
        try {
            // Start output buffering
            ob_start();
            
            // Generate QR to output buffer
            $this->generate($data, null, $level, $size, $margin);
            
            // Get buffer content
            $image_data = ob_get_contents();
            ob_end_clean();
            
            // Convert to base64
            if ($image_data) {
                return 'data:image/png;base64,' . base64_encode($image_data);
            }
            
            return '';
            
        } catch (Exception $e) {
            ob_end_clean(); // Clean buffer on error
            log_message('error', 'Failed to generate base64 QR: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Delete temporary file
     * 
     * @param string $file_path Path ke file
     * @return bool
     */
    public function delete_temp($file_path)
    {
        if (file_exists($file_path)) {
            return @unlink($file_path);
        }
        return false;
    }

    /**
     * Clear all cache files
     * 
     * @return int Number of files deleted
     */
    public function clear_cache()
    {
        $deleted = 0;
        
        if (is_dir($this->cachedir)) {
            $files = glob($this->cachedir . '*.png');
            foreach ($files as $file) {
                if (is_file($file) && @unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        log_message('info', "QR Cache cleared: {$deleted} files deleted");
        return $deleted;
    }

    /**
     * Check if phpqrcode library is loaded
     * 
     * @return bool
     */
    public function is_loaded()
    {
        return class_exists('QRcode');
    }
}