<?php 

/**
 * PHP wrapper for NCBI PubMed
 *   Extend Pubmed for term specific searching
 * 
 * @author  Tom Ploskina <tploskina@ambrygen.com>
 * @copyright Copyright (c) 2013 Ambry Genetics http://www.ambrygen.com
 * @license MIT http://opensource.org/licenses/MIT
 * @version 1.0
 */

namespace PubMed;
use SimpleXMLElement;

class Term extends PubMed
{
  /**
   * Return the URL of the search URL for searching by term
   * @return string URL of NCBI single article fetch
   */
  protected function getUrl()
  {
    return 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi';
  }

  /**
   * Specific to term searches
   * @return string parameter passed in the URI, eg, "&term=CFTR"
   */
  protected function getSearchName()
  {
    return 'term';
  }

  /**
   * Main function of this class, get the result xml
   * @param  string $term What are we searching?
   * @return array array of  New PubMed\Article objects
   */
  public function query($term)
  { 
    $content = $this->sendRequest($term);
    $xml = new SimpleXMLElement($content);
    $this->articleCount = (int) $xml->Count;
    $articles = array();

    if ($this->articleCount > 0) {
      foreach ($xml->IdList->Id as $k => $pmid) {
        $api = new PubMedId();
        $articles[] = $api->query($pmid);
      }

      return $articles;
    }

    return array();
  }
}
