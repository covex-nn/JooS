<?php

/**
 * @package JooS
 * @link http://www.php.net/manual/en/class.streamwrapper.php
 */

/**
 * An instance of this class is initialized as soon as a stream
 * function tries to access the protocol it is associated with.
 */
interface JooS_Stream_Interface
{

  /**
   * Constructs a new stream wrapper
   * 
   * Called when opening the stream wrapper,
   * right before stream::stream_open().
   * 
   * @link http://www.php.net/manual/en/streamwrapper.construct.php
   */
  public function __construct();

  /**
   * Close directory handle
   *
   * This method is called in response to closedir().
   * Any resources which were locked, or allocated, during opening and use of
   * the directory stream should be released.
   *
   * @link http://www.php.net/manual/en/streamwrapper.dir-closedir.php
   * @return boolean
   */
  public function dir_closedir();

  /**
   * Open directory handle
   *
   * This method is called in response to opendir().
   * 
   * @param string $path    Path
   * @param int    $options Options
   * 
   * @link http://www.php.net/manual/en/streamwrapper.dir-opendir.php
   * @return boolean
   */
  public function dir_opendir($path, $options);

  /**
   * Read entry from directory handle
   *
   * This method is called in response to readdir(). 
   *
   * @link http://www.php.net/manual/en/streamwrapper.dir-readdir.php
   * @return string
   */
  public function dir_readdir();

  /**
   * Rewind directory handle
   *
   * This method is called in response to rewinddir().
   *
   * Should reset the output generated by stream::dir_readdir().
   * i.e.: The next call to stream::dir_readdir() should
   * return the first entry in the location returned by stream::dir_opendir().
   *
   * @link http://www.php.net/manual/en/streamwrapper.dir-rewinddir.php
   * @return boolean
   */
  public function dir_rewinddir();

  /**
   * Create a directory
   *
   * This method is called in response to mkdir().
   *
   * @param string $path    Path
   * @param int    $mode    Mode
   * @param int    $options Options
   * 
   * @link http://www.php.net/manual/en/streamwrapper.mkdir.php
   * @return boolean
   */
  public function mkdir($path, $mode, $options);

  /**
   * Renames a file or directory
   *
   * This method is called in response to rename().
   *
   * Should attempt to rename pathFrom to pathTo
   *
   * @param string $pathFrom Path from
   * @param string $pathTo   Path to
   * 
   * @link http://www.php.net/manual/en/streamwrapper.rename.php
   * @return boolean
   */
  public function rename($pathFrom, $pathTo);

  /**
   * Removes a directory
   *
   * This method is called in response to rmdir().
   *
   * @param string $path    Path
   * @param int    $options Options
   * 
   * @link http://www.php.net/manual/en/streamwrapper.rmdir.php
   * @return boolean
   */
  public function rmdir($path, $options);

  /**
   * Retrieve the underlaying resources
   * 
   * This method is called in response to stream_select(). 
   * 
   * @param int $castAs Cast As
   * 
   * @link http://www.php.net/manual/en/streamwrapper.stream-cast.php
   * @return resource
   */
  public function stream_cast($castAs);

  /**
   * Close an resource
   *
   * This method is called in response to fclose().
   *
   * All resources that were locked, or allocated, by the wrapper
   * should be released.
   *
   * @link http://www.php.net/manual/en/streamwrapper.stream-close.php
   * @return null
   */
  public function stream_close();

  /**
   * Tests for end-of-file on a file pointer
   *
   * This method is called in response to feof(). 
   *
   * @link http://www.php.net/manual/en/streamwrapper.stream-eof.php
   * @return bool
   */
  public function stream_eof();

  /**
   * Flushes the output
   * 
   * This method is called in response to fflush().
   *
   * If you have cached data in your stream but not yet stored it into the
   * underlying storage, you should do so now.
   *
   * @link http://www.php.net/manual/en/streamwrapper.stream-flush.php
   * @return boolean
   */
  public function stream_flush();

  /**
   * Advisory file locking
   *
   * This method is called in response to flock(), when file_put_contents()
   * (when flags contains LOCK_EX), stream_set_blocking() and when closing
   * the stream (LOCK_UN).
   *
   * @param mode $operation Operation
   * 
   * @link http://www.php.net/manual/en/streamwrapper.stream-lock.php
   * @return boolean
   */
  public function stream_lock($operation);

  /**
   * Opens file or URL
   *
   * This method is called immediately after the wrapper is initialized
   * (f.e. by fopen() and file_get_contents()).
   *
   * @param string $path        Path
   * @param string $mode        Mode
   * @param int    $options     Options
   * @param string &$openedPath Opened Path
   * 
   * @link http://www.php.net/manual/en/streamwrapper.stream-open.php
   * @return boolean
   */
  public function stream_open($path, $mode, $options, &$openedPath);

  /**
   * Read from stream
   * 
   * This method is called in response to fread() and fgets().
   *
   * @param int $count Count
   * 
   * @link http://www.php.net/manual/en/streamwrapper.stream-read.php
   * @return string
   */
  public function stream_read($count);

  /**
   * Seeks to specific location in a stream
   * 
   * This method is called in response to fseek().
   *
   * The read/write position of the stream should be updated according to
   * the offset and whence.
   *
   * @param int $offset Offset
   * @param int $whence = SEEK_SET
   * 
   * @link http://www.php.net/manual/en/streamwrapper.stream-seek.php
   * @return bool
   */
  public function stream_seek($offset, $whence = SEEK_SET);

  /**
   * Change stream options
   * 
   * This method is called to set options on the stream.
   * 
   * SEEK_SET - Set position equal to offset bytes.
   * SEEK_CUR - Set position to current location plus offset.
   * SEEK_END - Set position to end-of-file plus offset.
   *
   * @param int $option Option
   * @param int $argOne Argument 1
   * @param int $argTwo Argument 2
   * 
   * @link http://www.php.net/manual/en/streamwrapper.stream-set-option.php
   * @return bool
   */
  public function stream_set_option($option, $argOne, $argTwo);

  /**
   * Retrieve information about a file resource
   * 
   * This method is called in response to fstat(). 
   *
   * @link http://www.php.net/manual/en/streamwrapper.stream-stat.php
   * @return array
   */
  public function stream_stat();

  /**
   * Retrieve the current position of a stream
   *
   * This method is called in response to ftell(). 
   *
   * @link http://www.php.net/manual/en/streamwrapper.stream-tell.php
   * @return int
   */
  public function stream_tell();

  /**
   * Write to stream
   * 
   * This method is called in response to fwrite(). 
   * 
   * @param string $data Data
   * 
   * @link http://www.php.net/manual/en/streamwrapper.stream-write.php
   * @return int
   */
  public function stream_write($data);

  /**
   * Delete a file
   * 
   * This method is called in response to unlink().
   * 
   * @param string $path Path
   * 
   * @link http://www.php.net/manual/en/streamwrapper.unlink.php
   * @return bool
   */
  public function unlink($path);

  /**
   * Retrieve information about a file
   * 
   * stream::url_stat — Retrieve information about a file
   *
   * @param string $path  Path
   * @param int    $flags Flags
   * 
   * @link http://www.php.net/manual/en/streamwrapper.url-stat.php
   * @return array
   */
  public function url_stat($path, $flags);

}
