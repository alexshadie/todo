<?php
/**
 * Created by PhpStorm.
 * User: tolmachyov
 * Date: 13.10.16
 * Time: 12:13
 */

namespace Todo\Project;


interface IProjectService {
	/**
	 * @param $text
	 * @return array [projectId, projectName, taskText]
	 */
	public function processText($text);
}