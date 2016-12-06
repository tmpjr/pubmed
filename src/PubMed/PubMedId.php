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

class PubMedId extends PubMed
{
  /**
   * Return the URL of the single PMID fetch
   * @return string URL of NCBI single article fetch
   */
  protected function getUrl()
  {
    return 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi';
  }

  /**
   * Specific to Single mode searches
   * @return string parameter passed in the URI, eg, "&id=23234234"
   */
  protected function getSearchName()
  {
    return 'id';
  }

  /**
   * Main function of this class, get the result xml, searching
   * by PubMedId (PMID)
   * @param  string $pmid PubMedID
   * @return object New PubMed\Article
   */
  public function query($pmid)
  {
    $content = $this->sendRequest($pmid);
    $xml = new SimpleXMLElement($content);
    if(count($xml->PubmedArticle)===1) {
      $this->articleCount = 1;
      return new Article($xml);
    } else {
      return false;
    }
  }
}
