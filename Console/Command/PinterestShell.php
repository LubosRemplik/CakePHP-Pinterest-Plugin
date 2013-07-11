<?php
App::uses('AppShell', 'Console/Command');
App::uses('Xml', 'Utility');
App::import('Vendor', 'Pinterest.simple_html_dom');
class PinterestShell extends AppShell {

	public $uses = array('Pinterest.PinterestPin');

	public function main() {
		$this->out($this->OptionParser->help());
	}
	
	public function parseRss() {
		$channel = Xml::toArray(Xml::build($this->args[0])->channel);
		$items = $channel['channel']['item'];
		$list = $this->PinterestPin->find('list', array('fields' => array('id', 'guid')));
		$data = array();
		foreach ($items as $item) {
			if (!in_array($item['guid'], $list)) {
				$html = file_get_html($item['guid']);
				$image = $html->find('img.pinImage', 0);
				if (is_object($image)) {
					$data[] = array(
						'guid' => $item['guid'],
						'title' => $item['title'],
						'image' => $image->attr['src'],
						'description' => strip_tags($item['description']),
						'created' => date('Y-m-d H:i:s', strtotime($item['pubDate']))
					);
				}
			}
		}
		if (!empty($data)) {
			if ($this->PinterestPin->saveAll($data)) {
				$this->out(__d('pinterest', '<success>All records saved sucesfully.</success>'));
				return true;
			} else {
				$this->err(__d('pinterest', 'Cannot save records.'));
				return false;
			}
		}
		$this->out(__d('pinterest', '<warning>No records saved.</warning>'));
	}
	
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->description(__d('pinterest', 'A console tool for managing pinterest'))
			->addSubcommand('parseRss', array(
				'help' => __d('pinterest', 'Parses pinterest rss feed and saves pins into DB'),
				'parser' => array(
					'arguments' => array(
						'rss' => array(
							'help' => __d('pinterest', 'Enter rss url.'),
							'required' => true,
						),
					),
				)
			))
			;

		return $parser;
	}

}
