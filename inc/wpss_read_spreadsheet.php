<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * @param $spreadsheet
 * @param int $offset
 *
 * @return array
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_read_spreadsheet( $spreadsheet, $offset = 0 ) {

	$reader   = IOFactory::load( $spreadsheet );
	$rows     = $reader->getActiveSheet();
	$get_rows = array();

	foreach ( $rows->getRowIterator() as $key => $row ):
		if ( $key > $offset ):
			$celliterator = $row->getCellIterator();
			$celliterator->setIterateOnlyExistingCells( false );
			$cells = array();
			foreach ( $celliterator as $cell ):
				$cells[] = (string) $cell->getValue();
			endforeach;
			$get_rows[] = $cells;
		endif;
	endforeach;

	return $get_rows;
}