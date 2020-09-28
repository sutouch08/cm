<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Excel library for Code Igniter applications
* Based on: Derek Allard, Dark Horse Consulting, www.darkhorse.to, April 2006
* Tweaked by: Moving.Paper June 2013
*/
class Export{
/*****************************************  Excel part  ****************************************/	
   /**
	 * Header (of document)
	 * @var string
	 */
        private $header = "<?xml version=\"1.0\" encoding=\"%s\"?\>\n<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">";

        /**
         * Footer (of document)
         * @var string
         */
        private $footer = "</Workbook>";

        /**
         * Lines to output in the excel document
         * @var array
         */
        private $lines = array();

        /**
         * Used encoding
         * @var string
         */
        private $sEncoding;
        
        /**
         * Convert variable types
         * @var boolean
         */
        private $bConvertTypes;
        
        /**
         * Worksheet title
         * @var string
         */
        private $sWorksheetTitle;
		
 /**
         * Set encoding
         * @param string Encoding type to set
         */
        public function setEncoding($sEncoding)
        {
        	$this->sEncoding = $sEncoding;
        }

        /**
         * Set worksheet title
         * 
         * Strips out not allowed characters and trims the
         * title to a maximum length of 31.
         * 
         * @param string $title Title for worksheet
         */
        public function setWorksheetTitle ($title)
        {
                //$title = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title);
                $title = substr ($title, 0, 31);
                $this->sWorksheetTitle = $title;
        }

        /**
         * Add row
         * 
         * Adds a single row to the document. If set to true, self::bConvertTypes
         * checks the type of variable and returns the specific field settings
         * for the cell.
         * 
         * @param array $array One-dimensional array with row content
         */
        private function addRow ($array)
        {
        	$cells = "";
                foreach ($array as $k => $v):
                        $type = 'String';
                        if ($this->bConvertTypes === true && is_numeric($v)):
                                $type = 'Number';
                        endif;
                        $v = htmlentities($v, ENT_COMPAT, $this->sEncoding);
                        $cells .= "<Cell><Data ss:Type=\"$type\">" . $v . "</Data></Cell>\n"; 
                endforeach;
                $this->lines[] = "<Row>\n" . $cells . "</Row>\n";
        }

        /**
         * Add an array to the document
         * @param array 2-dimensional array
         */
        public function addArray ($array, $convertType = true)
        {
			$this->bConvertTypes = $convertType;
                foreach ($array as $k => $v)
                        $this->addRow ($v);
        }


        /**
         * Generate the excel file
         * @param string $filename Name of excel file to generate (...xls)
         */
        public function excel($filename = 'excel-export', $encoding = 'UTF-8')
        {
				$this->sEncoding = $encoding;					
                // correct/validate filename
               //$filename = preg_replace('/[^aA-zZ0-9\_\-]/', '', $filename);
    			$this->setWorksheetTitle($filename);
                // deliver header (as recommended in php manual)
                header("Content-Type: application/x-msexcel; charset=" . $this->sEncoding);
                header("Content-Disposition: inline; filename=\"" . $filename . ".xls\"");

                // print out document to the browser
                // need to use stripslashes for the damn ">"
                echo stripslashes (sprintf($this->header, $this->sEncoding));
                echo "\n<Worksheet ss:Name=\"" . $this->sWorksheetTitle . "\">\n<Table>\n";
                foreach ($this->lines as $line)
                        echo $line;

                echo "</Table>\n</Worksheet>\n";
                echo $this->footer;
        }

		public function csv($filename="csv-export", $data, $field = false, $encoding = "UTF-8")
		{
			if($data != false)
			{
				$content = "";
				if($field != false)
				{
					$count = count($data[0]);
					$n = 0;
					foreach($data[0] as $key=>$value)
					{
						$n++;
						$content .= $key;
						if($n<$count){ $content .=","; }
						if($n >= $count){ $n = 0; }
					}
					$content .="\r\n";
				}
				
				foreach($data as $value)
				{
					$i = 0;
					foreach($value as $v)
					{
						$i++;
						$content .= $v;
						if($i<$count){ $content .=","; }
						if($i>=$count){ $i = 0; }
					}
					$content .="\r\n";
				}		
				header("Content-Type: application/csv; charset=".$encoding);
        		header("Content-Disposition: inline; filename=\"" . $filename . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				echo iconv("UTF-8",$encoding,$content);		
			}else{
				echo "No data";
			}
			
		}
	
}// End class
?>