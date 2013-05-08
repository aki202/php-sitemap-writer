<?php
/**
 * Sitemap writer
 * 
 * @author  Masayuki Akiyama
 * @version 0.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Sitemap
{
  private $_baseUrl;
  private $_items = array();
  
  function __construct($baseUrl)
  {
    $this->_baseUrl = $baseUrl;
  }
  
  public function addItem($path, $priority = NULL, $lastmod = NULL, $changefreq = NULL)
  {
    $this->_items[] = array(
      'url'        => $this->_baseUrl.$path,
      'priority'   => $priority,
      'lastmod'    => $lastmod,
      'changefreq' => $lastmod,
    );
  }

/**
 * Xmlファイルを作成
 * 
 * @param string $path
 * @return boolean
 */
  public function save($path)
  {
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    $minSeparators = 0;
    $i = 0;
    
    foreach ($this->_items as $item) {
      $separators = substr_count($item['url'], '/');
      if ($i == 0) $minSeparators = $separators;
      
      $xml .= '<url>';
      
      $xml .= sprintf('<loc>%s</loc>', $item['url']);
      
      $priority = $item['priority'];
      if (empty($priority)) {
        $priority = 1.00 - ($separators-$minSeparators)/10;
        if ($priority < 0.2) $priority = 0.2;
      }
      $xml .= sprintf('<priority>%.2f</priority>', $priority);

      if (!empty($item['lastmod'])) {
        $xml .= sprintf('<lastmod>%s</lastmod>', $item['lastmod']);
      }

      if (!empty($item['changefreq'])) {
        $xml .= sprintf('<changefreq>%s</changefreq>', $item['changefreq']);
      }

      $xml .= '</url>';
      $i++;
    }
    
    $xml .= '</urlset>';
    
    $fp = fopen($path, 'w');
    $r = fputs($fp, $xml);
    fclose($fp);
    
    return !($r === FALSE);
  }
  
}



