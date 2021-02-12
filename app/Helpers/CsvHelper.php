<?php

namespace App\Helpers;

/**
 * Example usage: Count them, then grab them in chunks of 10.
 * 
 * $filename = "part4.csv";
 * $rows = countCsvRows($filename);
 * $items_per_run = 10;
 * for ($i=0;$i<=$rows;$i+=$items_per_run+1) {
 *      $chunk = _csv_slice($filename, $i, $items_per_run);
 *      foreach ($chunk as $item) {
 *          echo "$i - item category = " .  $item->CurrentURL . "\n"; //Note CurrentURL is a case sensitive  
 *      }
 * }
 * 
 * Credit to https://gist.github.com/selwynpolit
 * @see https://gist.github.com/selwynpolit/7192fc22dce061ce902019d066347eb1
 * 
 * Adapted for use within this select environment
 */

class CsvHelper
{

    protected $filepath;

    protected $rowCount = false;

    protected $rows = false;

    protected $chunkSize = 100;

    private $currentPointerPos = 0;

    public function __construt($filepath, $chunkSize = false)
    {
        $this->filepath = $filepath;

        if ($chunkSize !== false) {
            $this->chunkSize = $chunkSize;
        }
    }

    /**
     * Count the number of rows in a CSV file excluding header row.
     *
     * @param string $filename
     *   CSV filename.
     *
     * @return int
     *   Number of rows.
     */
    private function countCsvRows()
    {
        ini_set('auto_detect_line_endings', true);
        $rowCount = 0;
        if (($handle = fopen($this->filename, "r")) !== false) {
            while (($row_data = fgetcsv($handle, 2000, ",")) !== false) {
                $rowCount++;
            }
            fclose($handle);
            // Exclude the headings.
            $rowCount--;
            $this->rowCount = $rowCount;
            return $rowCount;
        }
    }

    /**
     * Load desired_count rows from filename starting at position start.
     *
     * @param string $filename
     *   CSV filename.
     * @param int $start
     *   Starting position in file.
     * @param int $desired_count
     *   Count of rows requested.
     *
     * @return array|bool
     *   Array of Objects or FALSE
     */
    function chunkCsv()
    {
        $row = 0;
        $count = 0;
        $rows = array();
        if (($handle = fopen($this->filepath, "r")) === FALSE) {
            return false;
        }
        while (($rowData = fgetcsv($handle, 2000, ",")) !== FALSE) {
            // Grab headings.
            if ($row == 0) {
                $headings = $rowData;
                $row++;
                continue;
            }

            // Not there yet.
            if ($row++ < $this->currentPointerPos) {
                continue;
            }

            $rows[] = (object) array_combine($headings, $rowData);
            $count++;
            if ($count == $this->chunkSize) {
                return $rows;
            }
        }
        return $rows;
    }

    public function readFile($callback)
    {
        $this->countCsvRows();
        for ($this->currentPointerPos = 0; $this->currentPointerPos <= $this->rowCount; $this->currentPointerPos += $this->chunkSize + 1) {
            $chunk = $this->chunkCsv();
            foreach ($chunk as $item) {
                call_user_func_array($callback, [$item]);
            }
        }
    }
}
