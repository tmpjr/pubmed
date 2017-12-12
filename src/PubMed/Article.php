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

class Article implements \Serializable
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
    $this->pubstat = (string) $xml->PubmedArticle->PubmedData->PublicationStatus;
    $this->articleIds = $xml->PubmedArticle->PubmedData->ArticleIdList;
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
      'Pii'          => $this->getPii(),
      'PublicationStatus' =>$this->getPublicationStatus()
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
  *
  */
  private function findAID($type)
  {
    foreach($this->articleIds as $oneAID) {
        if($oneAID['IdType']==$type){
          return $oneAID;
        }
    }
  }

  /**
   * @return string
   */
  public function getDoid()
  {
      return (string) $this->findAID('doi');
  }

  /**
   * @return string
   */
  public function getPii()
  {
      return (string) $this->findAID('pii');
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
      if (!empty($this->xml->Journal->JournalIssue->PubDate->Year)) {
          return (string) $this->xml->Journal->JournalIssue->PubDate->Year;
      } else {
          $pubDate = (string) $this->xml->Journal->JournalIssue->PubDate->MedlineDate;
          if (preg_match('/^\d\d\d\d/', $pubDate)) {
              return substr($pubDate, 0, 4);
          } else {
              return (string) $this->xml->ArticleDate->Year;
          }
      }
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

  /**
   * Get the publication status
   * @return string PublicationStatus
   */
  public function getPublicationStatus()
  {
    return $this->pubstat;
  }
  
  /**
   * Custom serialize
   * @return string Serialized Article
   */
  public function serialize()
  {
      $ret = array();
      $ret['pmid'] = $this->pmid;
      $ret['xml'] = $this->xml->asXML();
      return serialize($ret);
  }
  
  /**
   * Custom unserialize
   */
  public function unserialize($data)
  {
      $ret = unserialize($data);
      $this->pmid = $ret['pmid'];
      $this->xml = simplexml_load_string($ret['xml']);
  }
}
