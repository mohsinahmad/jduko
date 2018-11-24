<?php
	class fwPageInfo {		

	/*=== Private variables ===*/
		private $pageSize;
		private $pageNo;
		private $pageCount;


	/*=== Constructor ===*/
	/*
	 * If Page number is not passed then it sets to default 1
	 * If Page size not passed then it sets to default page size set in the Global Constant DEFAULT_PAGE_SIZE */
		public function __construct($num='') {
			if($num!='')
				$this->setPageNumber($num);
			else
				$this->pageNo = 1;

			$this->pageSize = LP_PAGE_SIZE;
		}
		
		
		
	/*=== Gets & Sets ===*/
	
		/*--- Set Page Size ---*/
		public function setPageSize($value)
		{
			$value = (int)$value;
			if(!is_integer($value)) 
				$this->pageSize = LP_PAGE_SIZE;
			else
			{
				if($value < 1)
					$this->pageSize = LP_PAGE_SIZE;
				else
					$this->pageSize = $value;
			}
		}
		
		/*--- Get Page Size ---*/
		public function getPageSize()
		{
			return $this->pageSize;
		}
		

		/*--- Set Page Number ---*/
		public function setPageNumber($value)
		{
			$value = (int)$value;
			
			if(!is_integer($value)) 
				$this->pageNo = 1;
			else
			{
				if($value < 1)
					$this->pageNo = 1;
				else
					$this->pageNo = $value;
			}
		}

		/*--- Get Page Number ---*/
		public function getPageNumber()
		{
			return $this->pageNo;
		}

		
		/*--- Set Page Count ---*/
		public function setPageCount($value)
		{
			$this->pageCount = $value;
		}

		/*--- Get Page Count ---*/
		public function getPageCount()
		{
			return $this->pageCount;
		}

		
		
		/*
		 * Create Paging links  
		 */
		public function getPagerHTML($formName='', $targetDiv='', $listAction='')
		{
			$strHTML = "";
			
			if($this->pageNo > 1 )
			{
				$strHTML = '<a href="javascript:void(0);" onclick="showPage('.($this->pageNo-1);
				if($formName!='' && $targetDiv!='' && $listAction!='')
					$strHTML .= ", '". $formName ."', '". $targetDiv ."', '". $listAction ."'";
				$strHTML .= ')">&lt;&lt;</a>&nbsp;&nbsp;';
			}
			for($i=1; $i<=$this->pageCount; $i++)
			{
				if($i == $this->pageNo)
				{
					if($i > 1)
						$strHTML .= " | <strong>".$i."</strong>";
					else
						$strHTML .= "<strong>".$i."</strong>";
				}
				else
				{
					if($i > 1)
					{	
						$strHTML .= ' | <a href="javascript:void(0);" onclick="showPage('.$i;
						if($formName!='' && $targetDiv!='' && $listAction!='')
							$strHTML .= ", '". $formName ."', '". $targetDiv ."', '". $listAction ."'";
						$strHTML .= ')">'.$i.'</a>';
					}
					else
					{	
						$strHTML .= '<a href="javascript:void(0);" onclick="showPage('.$i;
						if($formName!='' && $targetDiv!='' && $listAction!='')
							$strHTML .= ", '". $formName ."', '". $targetDiv ."', '". $listAction ."'";
						$strHTML .= ')">'.$i.'</a>';
					}
				}
			}
	
			if($this->pageNo < $this->pageCount)
			{
				$strHTML .= '&nbsp;&nbsp;<a href="javascript:void(0);" onclick="showPage('.($this->pageNo+1);
				if($formName!='' && $targetDiv!='' && $listAction!='')
					$strHTML .= ", '". $formName ."', '". $targetDiv ."', '". $listAction ."'";
				$strHTML .= ')">&gt;&gt;</a>';
			}		
		
			echo $strHTML; //."-------- ". $formName .", ". $targetDiv;
		}
		
	}

?>
