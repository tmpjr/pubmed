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

class Article
{
  /**
   * SimpleXMLElement class will work on
   * @var object
   */
  private $xml;

  /**
   * PubMed ID
   * @var integer
   */
  private $pmid;

  /**
   * Constructor, init
   * @param SimpleXMLElement $xml The main xml object to work on
   */
  public function __construct(SimpleXMLElement $xml)
  {
    $this->xml = $xml->PubmedArticle->MedlineCitation->Article;
    $this->pmid = (string) $xml->PubmedArticle->MedlineCitation->PMID;
  }

  /**
   * Magic Method 
   * @return string return object print_r for debugging
   */
  public function __toString()
  {
    return print_r($this->xml, true);
  }

  /**
   * Get JSON result of all items
   * @return string JSON encoded string of results
   */
  public function toJson()
  {
    return json_encode($this->toArray());
  }

  /**
   * Run all getters on the xml object
   * @return array array of all getters
   */
  private function toArray()
  {
    return array(
      'PMID'         => $this->getPubMedId(),
      'Volume'       => $this->getVolume(),
      'Issue'        => $this->getIssue(),
      'PubYear'      => $this->getPubYear(),
      'PubMonth'     => $this->getPubMonth(),
      'PubDay'       => $this->getPubDay(),
      'ISSN'         => $this->getISSN(),
      'JournalTitle' => $this->getJournalTitle(),
      'JournalAbbr'  => $this->getJournalAbbr(),
      'Pagination'   => $this->getPagination(),
      'ArticleTitle' => $this->getArticleTitle(),
      'AbstractText' => $this->getAbstractText(),
      'Affiliation'  => $this->getAffiliation(),
      'Authors'      => $this->getAuthors(),
      'Doid'         => $this->getDoid(),
      'Pii'          => $this->getPii()
    );
  }

  /**
   * Return array of all results
   * @return array array of results
   */
  public function getResult()
  {
    return $this->toArray();
  }

  /**
   * Loop through authors, return Lastname First Initial
   * @return array The list of authors
   */
  public function getAuthors()
  {
    $authors = array();
    if (isset($this->xml->AuthorList)) {
      try {
        foreach ($this->xml->AuthorList->Author as $author) {
          $authors[] = (string) $author->LastName . ' ' . (string) $author->Initials;
        }
      } catch (Exception $e) {
        $a = $this->xml->AuthorList->Author;
        $authors[] = (string) $a->LastName .' '. (string) $a->Initials;
      }
    }

    return $authors;
  }

  public function getPubMedId()
  {
    return $this->pmid;
  }

  /**
   * @return string
   */
  public function getDoid()
  {
      return (string) $this->xml->ELocationID[1];
  }

  /**
   * @return string
   */
  public function getPii()
  {
      return (string) $this->xml->ELocationID[0];
  }

  /**
   * Get the volume from the SimpleXMLElement
   * @return string Journal Volume Number
   */
  public function getVolume()
  {
    return (string) $this->xml->Journal->JournalIssue->Volume;
  }

  /**
   * Get the JournalIssue from the SimpleXMLElement
   * @return string JournalIssue
   */
  public function getIssue()
  {
    return (string) $this->xml->Journal->JournalIssue->Issue;
  }

  /**
   * Get the PubYear from the SimpleXMLElement
   * @return string PubYear
   */
  public function getPubYear()
  {
    return (string) $this->xml->Journal->JournalIssue->PubDate->Year;
  }

  /**
   * Get the PubMonth from the SimpleXMLElement
   * @return string PubMonth
   */
  public function getPubMonth()
  {
    return (string) $this->xml->Journal->JournalIssue->PubDate->Month;
  }

  /**
   * Get the PubDay from the SimpleXMLElement
   * @return string PubDay
   */
  public function getPubDay()
  {
    return (string) $this->xml->Journal->JournalIssue->PubDate->Day;
  }

  /**
   * Get the ISSN from the SimpleXMLElement
   * @return string Journal ISSN
   */
  public function getISSN()
  {
    return (string) $this->xml->Journal->ISSN;
  }

  /**
   * Get the Journal Title from the SimpleXMLElement
   * @return string Journal Title
   */
  public function getJournalTitle()
  {
    return (string) $this->xml->Journal->Title;
  }

  /**
   * Get the ISOAbbreviation from the SimpleXMLElement
   * @return string ISOAbbreviation
   */
  public function getJournalAbbr()
  {
    return (string) $this->xml->Journal->ISOAbbreviation;
  }

  /**
   * Get the Pagination from the SimpleXMLElement
   * @return string Pagination
   */
  public function getPagination()
  {
    return (string) $this->xml->Pagination->MedlinePgn;
  }

  /**
   * Get the ArticleTitle from the SimpleXMLElement
   * @return string ArticleTitle
   */
  public function getArticleTitle()
  {
    return (string) $this->xml->ArticleTitle;
  }

  /**
   * Get the AbstractText from the SimpleXMLElement
   * @return string AbstractText
   */
  public function getAbstractText()
  {
    return (string) $this->xml->Abstract->AbstractText;
  }

  /**
   * Get the Affiliation from the SimpleXMLElement
   * @return string Affiliation
   */
  public function getAffiliation()
  {
    return (string) $this->xml->Affiliation;
  }
}
