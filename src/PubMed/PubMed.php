<?php

/**
 * PHP wrapper for NCBI PubMed
 *   Extend Pubmed for term specific searching
 * 
 * @author  Tom Ploskina <tploskinajr@gmail.com>
 * @copyright Copyright (c) 2013 http://tmpjr.me
 * @license MIT http://opensource.org/licenses/MIT
 * @version 1.0
 */

namespace PubMed;

abstract class PubMed
{
  /**
   * Curl resource handle
   * @var resource
   */
  protected $curl;

  /**
   * The number of seconds to wait while trying to connect.
   * @var integer
   */
  protected $connectionTimeout = 10;

  /**
   * The maximum number of seconds to allow cURL functions to execute.
   * @var integer
   */
  protected $timeout = 10;

  /**
   * Which database from NCBI to pull from
   * @var string
   */
  protected $db = 'PubMed';

  /**
   * The maximum number of articles to receive
   * @var integer
   */
  protected $returnMax = 10;

  /**
   * Which article to start at
   * @var integer
   */
  protected $returnStart = 0;

  /**
   * NCBI URL, should be set in child class
   * @var string
   */
  protected $url;

  /**
   * NCBI search URI name, should be set in child class
   * @var string
   */
  protected $searchTermName;

  /**
   * Amount of articles found
   * @var integer
   */
  protected $articleCount = 0;

  /**
   * Return mode from NCBI's API
   */
  const RETURN_MODE = 'xml';
  
  /**
   *  Initiate the cURL connection
   */
  public function __construct()
  {
    $this->curl = curl_init();
  }

  /**
   * Get the URL, specific to child classes
   * -- do not implement here
   * @return string url
   */
  protected function getUrl() {}

  /**
   * Get the URI variable name, specific to child classes
   * @return string eg, "term"
   */
  protected function getSearchName() {}

  /**
   * Return the article count
   * @return integer number of results
   */
  public function getArticleCount()
  {
    return intval($this->articleCount);
  }

  /**
   * Set the maximum returned articles
   * @param integer $value maximum return articles
   */
  public function setReturnMax($max)
  {
    return $this->returnMax = intval($max);
  }

  /**
   * At which article number to start?
   * @param integer $value The starting article index number
   */
  public function setReturnStart($start)
  {
    return $this->returnStart = intval($start);
  }

   /**
   * Send the request to NCBI, return the raw result,
   * throw \Ambry\Pubmed exception on error
   * @param  string $searchTerm What are we searching for?
   * @return string XML string
   */
  protected function sendRequest($searchTerm)
  {
    $url  = $this->getUrl();
    $url .= "?db=" . $this->db;
    $url .= "&retmax=" . intval($this->returnMax);
    $url .= "&retmode=" . self::RETURN_MODE;
    $url .= "&retstart=" . intval($this->returnStart);
    $url .= "&" . $this->getSearchName() . "=" . urlencode($searchTerm);

    curl_setopt($this->curl, CURLOPT_URL, $url);
    curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
    curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);

    $rs = curl_exec($this->curl);
    $curl_error = curl_error($this->curl);
    curl_close($this->curl);

    if ($curl_error) {
      throw new Exception($curl_error);
    }

    return $rs;
  }
}
