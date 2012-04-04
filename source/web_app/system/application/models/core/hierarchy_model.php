<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Apstraktni razred koji implementira metode za rukovanje hijerarhijskim podacima
 * u bazi podataka.
 *
 * Tablice moraju koristiti mix model adjacencty list i nested set.
 *
 * @package models
 *
 * @author Zeljko Tepsic <ztepsic@gmail.com>
 * @copyright ztepsic.com
 * @version 1.0.0
 * @since 2008-12-11
 *
 */
abstract class Hierarchy_model extends Model {

	/**
	 * Naziv tablice koja sadrzi Nested set hijerarhiju
	 *
	 * @var string
	 */
	private $categoryTable;

	/**
	 * Naziv lijevog atributa
	 *
	 * @var string
	 */
	private $leftAttributeName;

	/**
	 * Naziv desnog atributa
	 *
	 * @var string
	 */
	private $rightAttributeName;

	/**
	 * Naziv atributa koji sadrzi identifikator
	 *
	 * @var string
	 */
	private $idAttributeName;

	/**
	 * Naziv atributa koji sadrzi identifikator roditelja
	 *
	 * @var unknown_type
	 */
	private $parentIdAttributeName;

	/**
	 * Naziv atributa za sortiranje, po kojemu ce se
	 * izgraditi nested-set stablo
	 *
	 * @var string
	 */
	private $orderingAttributeName;



	/**
	 * Konstruktor
	 *
	 */
	public function __construct(){
		parent::Model();
	}

	/**
	 * Postavlja podatke/informaciji o tablici
	 *
	 * @param string $categoryTable - naziv tablice koja sadrzi Nested set hijerarhiju
	 * @param string $leftAttributeName - naziv lijevog atributa
	 * @param string $rightAttributeName - naziv desnog atributa
	 * @param string $idAttributeName - naziv atributa identifikatora
	 * @param string $parentIdAttributeName - naziv atributa identifikatora roditelja
	 */
	public function SetTableData($categoryTable,
	 $leftAttributeName,
	 $rightAttributeName,
	 $idAttributeName,
	 $parentIdAttributeName,
	 $orderingAttributeName){
		$this->categoryTable = $categoryTable;
		$this->leftAttributeName = $leftAttributeName;
		$this->rightAttributeName = $rightAttributeName;
		$this->idAttributeName = $idAttributeName;
		$this->parentIdAttributeName = $parentIdAttributeName;
		$this->orderingAttributeName = $orderingAttributeName;
	}

	/**
	 * Dohvaca cvor sa svim parametrima, te sa informacijom o dubini
	 *
	 * @param int $nodeId - identifikator cvora
	 * @return std_class - cvor
	 */
	public function GetNode($nodeId){
		$query = "
			SELECT
				node.*,
				(COUNT(parent." . $this->idAttributeName . ") - 1) AS depth
			FROM
			"	. $this->categoryTable . " as node,
			"	. $this->categoryTable . " as parent
			WHERE
				node." . $this->leftAttributeName . " BETWEEN parent." . $this->leftAttributeName ." AND parent." . $this->rightAttributeName . " AND
				node." . $this->idAttributeName . " = ?
			GROUP BY node." . $this->idAttributeName . "
			ORDER BY node." . $this->leftAttributeName . ";
		";

		return $this->db->query($query, $nodeId)->row();
	}

	/**
	 * Vraca hijerahiju u obliku stabla, sortirano po lijevoj strani
	 *
	 * @param int $parentId - cvor od kojeg se trazi ispis stabla
	 * @return std_class - stablo sortirano po lijevoj strani
	 */
	public function GetTree($parentId){
		$query = "
			SELECT
				node.*
			FROM
			"	. $this->categoryTable . " as node,
			"	. $this->categoryTable . " as parent
			WHERE
				node." . $this->leftAttributeName . " BETWEEN parent." . $this->leftAttributeName ." AND parent." . $this->rightAttributeName . " AND
				parent." . $this->idAttributeName . " = ?
			ORDER BY node." . $this->leftAttributeName . ";
		";

		return $this->db->query($query, $parentId)->result();
	}


	/**
	 * Vraca cijelo stablo hijerarhije sa ispisom rastuce dubine (depth).
	 * Atrubut sa informacijom o dubini zove se depth.
	 *
	 * @return std_class - cijelo stablo hijerarhije sa ispisom rastuce dubine (depth).
	 */
	public function GetFullTree(){
		$query = "
			SELECT
				node.*,
				(COUNT(parent." . $this->idAttributeName . ") - 1) AS depth
			FROM
			"	. $this->categoryTable . " as node,
			"	. $this->categoryTable . " as parent
			WHERE
				node." . $this->leftAttributeName . " BETWEEN parent." . $this->leftAttributeName ." AND parent." . $this->rightAttributeName . "
			GROUP BY node." . $this->idAttributeName . "
			ORDER BY node." . $this->leftAttributeName . ";
		";

		return $this->db->query($query)->result();

	}


	/**
	 * Dohvaca put od vrha(root) do cvora koji se trazi.
	 *
	 * @param int $nodeId - cvor za koji se trazi put do njega
	 * @return std_class - put do vrha(root) do trazenog cvora
	 */
	public function GetSinglePath($nodeId){
		$query = "
			SELECT
				parent.*,
				(COUNT(parent." . $this->idAttributeName . ") - 1) AS depth
			FROM
			"	. $this->categoryTable . " as node,
			"	. $this->categoryTable . " as parent
			WHERE
				node." . $this->leftAttributeName . " BETWEEN parent." . $this->leftAttributeName ." AND parent." . $this->rightAttributeName . " AND
				node." . $this->idAttributeName . " = ?
			ORDER BY parent." . $this->leftAttributeName . ";
		";

		return $this->db->query($query, $nodeId)->result();

	}


	/**
	 * Dohvaca svu djecu zadanog cvora.
	 *
	 * @param int $parentId - identifkator roditeljskog cvora
	 * @return std_class - djeca zadanog cvora
	 */
	public function GetChildren($parentId){
		$query = "
			SELECT
				*
			FROM
			"	. $this->categoryTable . "
			WHERE
			"	. $this->parentIdAttributeName . " = ?
			ORDER BY
			"	. $this->orderingAttributeName . " ASC;
		";

		return $this->db->query($query, $parentId)->result();
	}


	/**
	 * Dohvaca root cvor
	 *
	 * @return std_class (ROW) - root cvor
	 */
	public function GetRoot(){
		$query = "
		SELECT
			*
		FROM
		"	. $this->categoryTable . "
		WHERE
		"	. $this->parentIdAttributeName . " IS NULL
		ORDER BY
		"	. $this->orderingAttributeName . "
		";

		return $this->db->query($query)->result();
	}


	/**
	 * Osvjezava left-right brojeve zadane n-torke.
	 *
	 * @param int $nodeId - identifikator cvora
	 * @param int $left - lijeva strana
	 * @param int $right - desna strana
	 * @return boolean - true ako je akcija uspjela, inace false
	 */
	private function updateNestedSet($nodeId, $left, $right){
		$updateData = array();

		if(!empty($left)){
			$updateData[$this->leftAttributeName] = $left;
		}

		if(!empty($right)){
			$updateData[$this->rightAttributeName] = $right;
		}

		$this->db->where($this->idAttributeName, $nodeId);
		return $this->db->update($this->categoryTable, $updateData);

	}


	/**
	 * Izgraduje nested-set stablo
	 *
	 * @param int $parentId - identifikator roditelja
	 * @param int $left - lijeva vrijednost
	 * @return int - desna vrijednost
	 */
	public function RebuildTree($nodeId, $left){
    	// desna vrijednost ovog cvora je lijeva vrijednost + 1
    	$right = $left + 1;

    	// dohvati svu djecu za ovaj cvor
    	$children = $this->GetChildren($nodeId);

    	foreach ($children as $child){
    		// rekurzivna funkcija za svako djete trenutnog cvora
    		// $right je trenutna right vrijednost, koja se mjenja povratnom vrijednosti
    		// funkcije

    		$right = $this->RebuildTree($child->{$this->idAttributeName}, $right);
    	}


    	$this->updateNestedSet($nodeId, $left, $right);

    	return $right + 1;

    }

}

?>