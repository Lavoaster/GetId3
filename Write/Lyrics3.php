<?php

namespace GetId3\Write;

use GetId3\GetId3Core;

/////////////////////////////////////////////////////////////////
/// GetId3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// write.lyrics3.php                                           //
// module for writing Lyrics3 tags                             //
// dependencies: module.tag.lyrics3.php                        //
//                                                            ///
/////////////////////////////////////////////////////////////////

/**
 * module for writing Lyrics3 tags
 *
 * @author James Heinrich <info@getid3.org>
 *
 * @link http://getid3.sourceforge.net
 * @link http://www.getid3.org
 *
 * @uses GetId3\Module\Tag\Lyrics3
 */
class Lyrics3
{
    public $filename;
    public $tag_data;
    //var $lyrics3_version = 2;       // 1 or 2
    /**
     * @var array
     */
    public $warnings = array(); // any non-critical errors will be stored here
    /**
     * @var array
     */
    public $errors = array(); // any critical errors will be stored here

    /**
     * @return bool
     */
    public function __construct()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function WriteLyrics3()
    {
        $this->errors[] = 'WriteLyrics3() not yet functional - cannot write Lyrics3';

        return false;
    }
    public function DeleteLyrics3()
    {
        // Initialize GetId3 engine
        $getID3 = new GetId3Core();
        $ThisFileInfo = $getID3->analyze($this->filename);
        if (isset($ThisFileInfo['lyrics3']['tag_offset_start']) && isset($ThisFileInfo['lyrics3']['tag_offset_end'])) {
            if (is_readable($this->filename) && is_writable($this->filename) && is_file($this->filename) && ($fp = fopen($this->filename, 'a+b'))) {
                flock($fp, LOCK_EX);
                $oldignoreuserabort = ignore_user_abort(true);

                fseek($fp, $ThisFileInfo['lyrics3']['tag_offset_end'], SEEK_SET);
                $DataAfterLyrics3 = '';
                if ($ThisFileInfo['filesize'] > $ThisFileInfo['lyrics3']['tag_offset_end']) {
                    $DataAfterLyrics3 = fread($fp, $ThisFileInfo['filesize'] - $ThisFileInfo['lyrics3']['tag_offset_end']);
                }

                ftruncate($fp, $ThisFileInfo['lyrics3']['tag_offset_start']);

                if (!empty($DataAfterLyrics3)) {
                    fseek($fp, $ThisFileInfo['lyrics3']['tag_offset_start'], SEEK_SET);
                    fwrite($fp, $DataAfterLyrics3, strlen($DataAfterLyrics3));
                }

                flock($fp, LOCK_UN);
                fclose($fp);
                ignore_user_abort($oldignoreuserabort);

                return true;
            } else {
                $this->errors[] = 'Cannot fopen('.$this->filename.', "a+b")';

                return false;
            }
        }
        // no Lyrics3 present
        return true;
    }
}
