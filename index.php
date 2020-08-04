<?php


class index
{
	private $list;
	private $tree;

	public function __construct()
	{
		$this->setList('./list.json');
		$this->setTree('./tree.json');
	}

	private function setList(string $listFile)
	{
		if (is_array($array = $this->getArrayFromJSONFile($listFile)))
		{
			$this->list = $array;
		}
	}

	private function setTree(string $treeFile)
	{
		if (is_array($array = $this->getArrayFromJSONFile($treeFile)))
		{
			$this->tree = $array;
		}
	}

	private function getArrayFromJSONFile(string $filename)
	{
		try
		{
			$file = file_get_contents($filename);
			$json = json_decode($file, true);
		} catch (Exception $e)
		{
			return "Something went wrong!";
		}

		return $json;
	}

	public function getList()
	{
		return $this->list;
	}

	public function getTree()
	{
		return $this->tree;
	}

	/**
	 * @return array|bool
	 */
	public function getTreeWithName()
	{
		return json_encode($this->recAddNameToTree($this->tree));
	}

	private function findNameInList(int $id)
	{
		$list = $this->list;

		if (!is_array($list)) return false;

		foreach($list as $item)
		{
			if (key_exists('category_id', $item) && intval($item['category_id']) === $id)
			{
				return $item['translations']['pl_PL']['name'];
			}
		}

		return "";
	}

	/**
	 * @param array $tree
	 * @return array|bool
	 */
	private function recAddNameToTree(array $tree)
	{
		if (!is_array($tree)) return false;

		foreach($tree as $key => $branch)
		{
			$tree[$key]['name'] = $this->findNameInList(intval($branch['id']));

			if (key_exists('children', $branch) && is_array($branch['children']))
			{
				$tree[$key]['children'] = $this->recAddNameToTree($branch['children']);
			}
		}

		return $tree;
	}
}


$index = new Index();
print_r($index->getTreeWithName());
