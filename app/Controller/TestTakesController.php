<?php

App::uses('AppController', 'Controller');
App::uses('TestsService', 'Lib/Services');
App::uses('QuestionsService', 'Lib/Services');
App::uses('TestTakesService', 'Lib/Services');
App::uses('AnswersService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService','Lib/Services');
App::uses('SchoolYearsService','Lib/Services');
// App::uses('TestsService', 'Lib/Services');

class TestTakesController extends AppController
{

	public $uses = array('Test', 'Question', 'TestTake');

	public function beforeFilter()
	{
		// $this->TestsService = new TestsService(); 
		$this->QuestionsService = new QuestionsService();
		$this->TestTakesService = new TestTakesService();
		$this->AnswersService = new AnswersService();
		$this->SchoolClassesService = new SchoolClassesService();
		$this->TestsService = new TestsService();
		$this->SchoolLocationsService = new SchoolLocationsService();
		$this->SchoolYearsService = new SchoolYearsService();

		parent::beforeFilter();
	}

	public function get_date_period() {

		$this->autoRender = false;
		$date = $this->request->data['date'];

		$periods = $this->TestsService->getPeriods(true);

		foreach($periods as $period) {
			if(strtotime($period['start_date']) < strtotime($date) && strtotime($period['end_date']) > strtotime($date)) {
				echo $period['id'];
				die;
			}
		}
	}

	public function add_retake($test_take_id = null) {

		if($this->request->is('post')) {
			$data = $this->request->data;

			$test_take = $data['TestTake'];

			$users = [];

			foreach($data['User'] as $id => $val) {
				if(!empty($val)) {
					$users[] = $id;
				}
			}

			$test = $this->TestsService->getTest($test_take['test_id']);
			$check = $this->TestTake->checkRetake($test_take, $test);

			$test_take['time_start'] = date('Y-m-d 00:00:00', strtotime($test_take['time_start']));
			$test_take['retake'] = 1;

			$result = $this->TestTakesService->addRetake($test_take);

			if(isset($result['id'])) {
				$result = $this->TestTakesService->addParticipants($result['id'], $users);

				$this->formResponse(
					!empty($result),
					$result
				);
			}else{
				$this->formResponse(
					false,
					[]
				);
			}
		}

		$inviligators = $this->TestsService->getInvigilators();
		$newInviligators = [];

		foreach($inviligators as $inviligator) {
			$newInviligators[$inviligator['id']] = $inviligator['name_first'] . ' ' . $inviligator['name_suffix'] . ' ' .$inviligator['name'];
		}

		if(!empty($test_take_id)) {
			$test_take = $this->TestTakesService->getTestTake($test_take_id);
			$this->set('test_take', $test_take);
		}
		$periods = $this->TestsService->getPeriods();

		$participants = $this->TestTakesService->getParticipants($test_take_id);

		$this->set('participants', $participants);
		$this->set('periods', $periods);
		$this->set('inviligators', $newInviligators);
	}

	public function add($test_id = '') {

		if($this->request->is('post')) {

			$this->autoRender = false;

			$data = $this->request->data;
			$test_takes = $data['TestTake'];

			$checkAll = true;

			foreach($test_takes as $test_take) {

				if(!empty($test_take['test_id'])) {


					$class = $this->SchoolClassesService->getClass($test_take['class_id']);
					$test = $this->TestsService->getTest($test_take['test_id']);

					if(strtotime($test_take['date']) == 0) {
						$this->formResponse(false, [
							'errors' => [
								'Datum is incorrect'
							]
						]);

						die;
					}

					if(
						$test['education_level_year'] != $class['education_level_year'] ||
						$test['period']['school_year_id'] != $class['school_year_id']
					) {
						$this->formResponse(false, [
							'errors' => [
								'Leerjaren van klassen en toetsen komen niet overeen.'
							]
						]);

						die;
					}

					$check = $this->TestTake->check($test_take, $test);
					if(!$check['status']) {
						$this->formResponse(
							false,
							[
								'errors' => $check['errors']
							]
						);

						die;
					}
				}
			}

			if($checkAll == false) {
				$this->formResponse(false, []);
				die;
			}

			$result = "";

			foreach($test_takes as $test_take) {
				if (!empty($test_take['test_id'])) {
					$test_take['time_start'] = date('Y-m-d 00:00:00', strtotime($test_take['date']));
					$test_take['retake'] = 0;
					$test_take['test_take_status_id'] = 1;

					if(!isset($test_take['weight'])) 
						$test_take['weight'] = 0;

					$result = $this->TestTakesService->add($test_take);
				}
			}

			$this->formResponse(
				!empty($result),
				$result
			);
			die;

		}else {

			$params['filter'] = ['current_school_year' => 1];

			$education_levels = $this->TestsService->getEducationLevels();
			$periods = $this->TestsService->getPeriods(false, $params);
			$subjects = $this->TestsService->getSubjects();
			$kinds = $this->TestsService->getKinds();
			$classes = $this->TestsService->getClasses($params);
			$locations = $this->SchoolLocationsService->getSchoolLocations();

			$inviligators = $this->TestsService->getInvigilators();

			$newInviligators = [];

			foreach($inviligators as $inviligator) {
				$newInviligators[$inviligator['id']] = $inviligator['name_first'] . ' ' . $inviligator['name_suffix'] . ' ' .$inviligator['name'];
			}

			if (!empty($test_id)) {
				$test = $this->TestsService->getTest($test_id);
				$test_name = $test['name'];
				$this->set('test', $test);
			} else {
				$test_name = 'Selecteer';
			}

			$this->set('classes', $classes);
			$this->set('inviligators', $newInviligators);
			$this->set('test_name', $test_name);
			$this->set('education_levels', $education_levels);
			$this->set('kinds', $kinds);
			$this->set('periods', $periods);
			$this->set('subjects', $subjects);
			$this->set('test_id', $test_id);
			$this->set('locations', $locations);
		}
	}

	public function edit($take_id) {

		$take = $this->TestTakesService->getTestTake($take_id);

		if($this->request->is('post')) {
			$this->autoRender = false;

			$data = $this->request->data['TestTake'];
			$test = $this->TestsService->getTest($take['test_id']);
			$check = $this->TestTake->checkEdit($data, $take['retake'] == 1, $test);

			if(!$check['status']) {
				$this->formResponse(
					$check['status'],
					$check['errors']
				);
			}else{
				$result = $this->TestTakesService->editTestTake($take_id, $data);

				$this->formResponse(
					true,
					[]
				);
				die;
			}
		}

		$education_levels = $this->TestsService->getEducationLevels();
		$periods = $this->TestsService->getPeriods();
		$subjects = $this->TestsService->getSubjects();
		$kinds = $this->TestsService->getKinds();
		$classes = $this->TestsService->getClasses();
		$school_location = $this->SchoolLocationsService->getSchoolLocation($take['test']['author']['school_location_id']);

		$inviligators = $this->TestsService->getInvigilators();

		$newInviligators = [];

		foreach($inviligators as $inviligator) {
			$newInviligators[$inviligator['id']] = $inviligator['name_first'] . ' ' . $inviligator['name_suffix'] . ' ' .$inviligator['name'];
		}

		if (!empty($test_id)) {
			$test = $this->TestsService->getTest($test_id);
			$test_name = $test['name'];
		} else {
			$test_name = 'Selecteer';
		}

		$this->set('classes', $classes);
		$this->set('inviligators', $newInviligators);
		$this->set('test_name', $test_name);
		$this->set('education_levels', $education_levels);
		$this->set('kinds', $kinds);
		$this->set('periods', $periods);
		$this->set('subjects', $subjects);

		$this->set('take', $take);
		$this->set('take_id', $take_id);
		$this->set('is_rtti_school_location', $school_location['is_rtti_school_location']);
	}

	public function csv_export($take_id) {
		$export = $this->TestTakesService->getExport($take_id);

		$this->response->body($export);
		$this->response->type('csv');
		////$this->response->header('Content-Disposition: attachment; filename=“export.csv”')
		$this->response->header('Content-Disposition', 'attachment; filename=export.csv');
		return $this->response;

	}

	public function force_taken_away($take_id, $participant_id) {
		$this->autoRender = false;
		$this->TestTakesService->updateParticipantStatus($take_id, $participant_id, 6);
	}

	public function force_planned($take_id, $participant_id) {
		$this->autoRender = false;
		$this->TestTakesService->updateParticipantStatus($take_id, $participant_id, 3);
	}

	public function set_taken($take_id) {
		$this->autoRender = false;
		$this->TestTakesService->updateStatus($take_id, 6);
	}

	public function add_class($class_id) {
		$this->autoRender = false;
		$take_id = $this->Session->read('take_id');
		echo $this->TestTakesService->addClass($take_id, $class_id) ? 1 : 0;
	}

	public function add_participants($take_id) {
		$this->Session->write('take_id', $take_id);
		$classes = $this->TestsService->getClasses();

		$this->set('classes', $classes);
	}

	public function remove_participant($take_id, $participant_id) {
		$this->autoRender = false;
		$this->TestTakesService->removeParticipant($take_id, $participant_id);
	}

	public function view($take_id) {
		$take = $this->TestTakesService->getTestTake($take_id);


		$this->set('take', $take);
		$this->set('take_id', $take_id);

		if($take['test_take_status_id'] < 6) {
			$this->render('view_planned', 'ajax');
		}elseif($take['test_take_status_id'] == 8) {
			$params['with'] = ['statistics'];

			$participants = $this->TestTakesService->getParticipants($take_id, $params);
			$this->set('participants', $participants);


			$this->render('view_discussed', 'ajax');
		}elseif($take['test_take_status_id'] == 9) {

			$params['with'] = ['statistics'];

			$participants = $this->TestTakesService->getParticipants($take_id, $params);

			$this->set('participants', $participants);
			// $this->set('is_rtti_test', $take);
			$this->render('view_rated', 'ajax');
		}else{
			$this->render('view_taken', 'ajax');
		}
	}

	public function rated_info($take_id, $participant_id) {
		$participant = $this->TestTakesService->getParticipant($take_id, $participant_id);

		$this->set('participant', $participant);
		$this->set('take_id', $take_id);
		$this->set('participant_id', $participant_id);
	}

	public function load_participants($take_id) {
		$participants = $this->TestTakesService->getParticipants($take_id);
		$take = $this->TestTakesService->getTestTake($take_id);

		$this->set('participants', $participants);
		$this->set('take_id', $take_id);

		$this->set('status', $take['test_take_status_id']);
	}

	public function hand_in() {
		$this->autoRender = false;
		$participant_id = $this->Session->read('participant_id');

		$take_id = $this->Session->read('take_id');

		$this->Session->delete('drawing_pad');
		$this->Session->delete('drawing_data');

		$this->TestTakesService->updateParticipantStatus($take_id, $participant_id, 4);
	}

	public function start_take_participant() {

		$this->autoRender = false;
		$participent_id = $this->Session->read('participant_id');
		$take_id = $this->Session->read('take_id');
		$this->Session->delete('drawing_pad');
		$this->Session->delete('drawing_data');
		$this->Session->delete('take_question_index');
		$this->Session->delete('active_question');

		if(!$this->TestTakesService->startParticpantTest($take_id, $participent_id)) {
			echo 'error';
		}
	}

	public function start_multiple() {
		$params = $this->request->data;

		$params['order']['time_start'] = 'asc';
		$params['filter']['time_start_from'] = date('Y-m-d 00:00:00');
		$params['filter']['time_start_to'] = date('Y-m-d 00:00:00', strtotime('+1 day'));
		$params['filter']['test_take_status_id'] = 1;

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function participant_info($take_id, $participant_id) {
		if($this->request->is('post') || $this->request->is('put')) {
			$this->TestTakesService->setParticipantNote($take_id, $participant_id, $this->request->data['Participant']['invigilator_note']);

			$this->formResponse(
				true,
				[]
			);

			die;
		}

		$participant = $this->TestTakesService->getParticipant($take_id, $participant_id);
		$answers = $this->AnswersService->getParticipantQuestionAnswers($participant_id);

		$question_index = null;

		$i = 1;

		foreach($answers as $answer) {

			if($answer['id'] == $participant['answer_id']) {
				$question_index = $i;
			}
			$i++;
		}

		$events = $this->TestTakesService->getEvents($take_id, $participant_id, false);


		$this->set('events', $events);
		$this->set('participant', $participant);
		$this->set('test_id', $take_id);
		$this->set('question_index', $question_index);
		$this->render('participant_info', 'ajax');
	}

	public function discuss($take_id, $question_index = null) {
		$this->Session->write('take_id', $take_id);
		$take = $this->TestTakesService->getTestTake($take_id);

		$participant_id = $take['test_participant']['id'];
		$participant_status = $take['test_participant']['test_take_status_id'];

		$this->Session->write('participant_id', $participant_id);

		switch($participant_status) {
			case 4:
			case 6:
				$view = 'take_discuss_waiting';
				break;
			case 7:
				$rating = $this->TestTakesService->getRating($take_id);

				if(!empty($rating['id'])) {
					$this->Session->write('rating_id', $rating['id']);

					$this->set('rating', $rating);
					$view = 'take_discuss';
				}else{
					$view = 'take_discuss_waiting_next';
				}

				break;

			case 8:
				$view = 'take_discussed';
				break;

			default:
				die('Niet te bespreken');
				break;
		}

		$this->set('take', $take);
		$this->set('take_id', $take_id);

		$this->render($view, 'ajax');
	}

	public function rate_teacher_question($take_id, $question_index = 0) {

		$take = $this->TestTakesService->getTestTake($take_id);
		$allQuestions = $this->TestsService->getQuestions($take['test_id']);
		$participants = $this->TestTakesService->getParticipants($take_id);

		$this->Session->write('take_id', $take_id);

		$questions = [];

		foreach ($allQuestions as $allQuestion) {
			if ($allQuestion['question']['type'] == 'GroupQuestion') {
				foreach($allQuestion['question']['group_question_questions'] as $item) {
					$item['group_id'] = $allQuestion['question']['id'];
					$questions[] = $item;
				}
			} else {
				$questions[] = $allQuestion;
			}
		}

		$this->Session->write('active_question', $questions[$question_index]);

		$this->set('question_id', $questions[$question_index]['question_id']);
		$this->set('questions', $questions);
		$this->set('question_index', $question_index);
		$this->set('participants', $participants);
		$this->set('take_id', $take_id);
	}

	public function normalization($take_id) {

		if($this->request->is('post') || $this->request->is('put')) {
			$this->TestTakesService->saveNormalization($take_id, $this->request->data, false);
			die;
		}

		$test_take = $this->TestTakesService->getTestTake($take_id, [
			'with' => [
				'averages'
			]
		]);

		$this->set('test_take', $test_take);
		$this->set('take_id', $take_id);
	}

	public function normalization_preview($take_id) {
		$results = $this->TestTakesService->saveNormalization($take_id, $this->request->data, true);
		$this->set('results', $results);
	}

	public function rate_teacher_participant($take_id, $participant_index = 0) {
		$take = $this->TestTakesService->getTestTake($take_id);
		$allQuestions = $this->TestsService->getQuestions($take['test_id']);
		$participants = $this->TestTakesService->getParticipants($take_id);

		$this->Session->write('take_id', $take_id);
		$this->Session->write('take_data_' . $take_id, $take);

		$questions = [];

		foreach ($allQuestions as $allQuestion) {
			if ($allQuestion['question']['type'] == 'GroupQuestion') {
				foreach($allQuestion['question']['group_question_questions'] as $item) {
					$item['group_id'] = $allQuestion['question']['id'];
					$questions[] = $item;
				}
			} else {
				$questions[] = $allQuestion;
			}
		}

		$newParticipants = [];

		foreach($participants as $participant) {
			if(in_array($participant['test_take_status_id'], [4,5,6,7,8,9])) {
				$newParticipants[] = $participant;
			}
		}

		$participants = $newParticipants;

		$this->Session->write('active_participant', $participants[$participant_index]);
		$this->set('participant_id', $participants[$participant_index]['id']);
		$this->set('questions', $questions);
		$this->set('participant_index', $participant_index);
		$this->set('participants', $participants);
		$this->set('take_id', $take_id);
	}

	public function rate_teacher_participant_answer($take_id, $question_id) {
		$take = $this->Session->read('take_data_' . $take_id);


	}

	public function view_results($take_id, $participant_id) {
		$take = $this->TestTakesService->getTestTake($take_id);
		$allQuestions = $this->TestsService->getQuestions($take['test_id']);

		$questions = [];

		foreach ($allQuestions as $allQuestion) {
			if ($allQuestion['question']['type'] == 'GroupQuestion') {
				foreach($allQuestion['question']['group_question_questions'] as $item) {
					$questions[] = $item;
				}
			} else {
				$questions[] = $allQuestion;
			}
		}

		$this->set('questions', $questions);
		$this->set('participant_id', $participant_id);
	}

	public function rate_teacher_random($take_id, $percentage = 50) {

		$answers = $this->TestTakesService->getRandomAnswerRatings($take_id, $percentage);

		$this->set('answers', $answers);
	}

	public function rate_teacher_score($participant_id = null, $question_id = null, $editable = true) {

		if($this->request->is('post')) {
			$data = $this->request->data;

			$answer_id = $data['answer_id'];
			$score = $data['score'];
			$take_id = $this->Session->read('take_id');
			$user_id = AuthComponent::user('id');

			$rating_id = $data['rating_id'];

			if(empty($rating_id)) {
				$this->TestTakesService->saveTeacherScore($answer_id, $score, $user_id, $take_id);
			}else{
				$this->TestTakesService->updateTeacherScore($rating_id, $score);
			}

			die;
		}

		$answer = $this->AnswersService->getParticipantQuestionAnswer($question_id, $participant_id, true);

		if(!isset($answer['answer_ratings'])) {
			echo 'Vraag niet gemaakt';
			die;
		}

		if(!empty($question_id)) {

			if($this->Session->check('rate-question-cache-' . $question_id)) {
				$question = $this->Session->read('rate-question-cache-' . $question_id);
			}else {
				$question = $this->QuestionsService->getSingleQuestion($question_id);
				$this->Session->write('rate-question-cache-' . $question_id, $question);
			}
			$question['question'] = $question;
		}else{
			$question = $this->Session->read('active_question');
		}

		$this->set('participant_id', $participant_id);
		$this->set('question_id', $question_id);
		$this->set('question', $question);
		$this->set('answer', $answer);
		$this->set('editable', $editable);
	}

	public function rate_teacher_answer($participant_id, $question_id) {
		$this->autoRender = false;

		$answer = $this->AnswersService->getParticipantQuestionAnswer($question_id, $participant_id, true);

		if(!$answer) {
			echo 'Vraag niet gemaakt';
			die;
		}

		$this->Session->write('participant_answer.' . $participant_id, $answer);

		switch($answer['question']['type']) {
			case "OpenQuestion":
				$view = 'rate_open';
				break;

			case "CompletionQuestion":
				if($answer['question']['subtype'] == 'completion') {
					$view = 'rate_completion';
				}elseif($answer['question']['subtype'] == 'multi') {
					$view = 'rate_multi_completion';
				}
				break;

			case "MatchingQuestion":
				if($answer['question']['subtype'] == 'Matching') {
					$view = 'rate_matching';
				}elseif($answer['question']['subtype'] == 'Classify') {
					$view = 'rate_classify';
				}
				break;

			case "MultipleChoiceQuestion":
				if($answer['question']['subtype'] == 'MultiChoice' || $answer['question']['subtype'] == 'MultipleChoice') {
					$view = 'rate_multiple_choice';
				}elseif($answer['question']['subtype'] == 'TrueFalse') {
					$view = 'rate_true_false';
				}elseif($answer['question']['subtype'] == 'ARQ') {
					$view = 'rate_arq';
				}
				break;

			case "RankingQuestion":
				$view = 'rate_ranking';
				break;

			case "DrawingQuestion":
				$view = 'rate_drawing';
				break;

			default:
				die;
				break;
		}

		if(empty($answer['json'])) {
			$view = 'rate_empty';
		}

		$answer['answer'] = $answer;

		$this->set('rating', $answer);
		$this->set('question_id', $question_id);
		$this->render($view, 'ajax');
	}

	public function rate($take_id, $user_id = null) {

		$rating = $this->TestTakesService->getRating($take_id, $user_id);

		if(!isset($rating['id'])) {
			echo 'Geen antwoord om weer te geven';
			die;
		}

		switch($rating['answer']['question']['type']) {
			case "OpenQuestion":
				$view = 'rate_open';
				break;

			case "CompletionQuestion":
				if($rating['answer']['question']['subtype'] == 'completion') {
					$view = 'rate_completion';
				}elseif($rating['answer']['question']['subtype'] == 'multi') {
					$view = 'rate_multi_completion';
				}
				break;

			case "MatchingQuestion":
				if($rating['answer']['question']['subtype'] == 'Matching') {
					$view = 'rate_matching';
				}elseif($rating['answer']['question']['subtype'] == 'Classify') {
					$view = 'rate_classify';
				}
				break;

			case "MultipleChoiceQuestion":
				if($rating['answer']['question']['subtype'] == 'MultiChoice' || $rating['answer']['question']['subtype'] == 'MultipleChoice') {
					$view = 'rate_multiple_choice';
				}elseif($rating['answer']['question']['subtype'] == 'TrueFalse') {
					$view = 'rate_true_false';
				}elseif($rating['answer']['question']['subtype'] == 'ARQ') {
					$view = 'rate_arq';
				}
				break;

			case "RankingQuestion":
				$view = 'rate_ranking';
				break;

			case "DrawingQuestion":
				$view = 'rate_drawing';
				break;

			default:
				die;
				break;
		}

		if(empty($rating['answer']['json'])) {
			$view = 'rate_empty';
		}

		$this->set('rating', $rating);
		$this->render($view, 'ajax');
	}

	public function set_final_rates($take_id) {

		$take = $this->TestTakesService->getTestTake($take_id);

		if(!empty($take['retake_test_take_id'])) {
			$retake_participants = $this->TestTakesService->getParticipants($take['retake_test_take_id']);
			$this->set('retake_participants', $retake_participants);
		}

		$participants = $this->TestTakesService->getParticipants($take_id);
		$test_take = $this->TestTakesService->getTestTake($take_id);

		$this->set('take', $test_take);
		$this->set('participants', $participants);
		$this->set('take_id', $take_id);
	}

	public function set_final_rate($test_take_id, $participant_id, $rate) {

		$this->autoRender = false;
		$this->TestTakesService->setParticipantRating($test_take_id, $participant_id, $rate);
	}

	public function mark_rated($take_id) {
		$this->autoRender = false;
		$this->TestTakesService->markRated($take_id);
	}

	public function set_rating() {
		$this->autoRender = false;
		$rating_id = $this->Session->read('rating_id');
		$this->TestTakesService->setRating($rating_id, $this->request->data['rating']);
	}

	public function take($take_id, $question_index = null, $clean = false) {
		$this->Session->write('take_id', $take_id);
		$take = $this->TestTakesService->getTestTake($take_id);

		$participant_id = $take['test_participant']['id'];
		$participant_status = $take['test_participant']['test_take_status_id'];

		$this->Session->write('participant_id', $participant_id);

		switch($participant_status) {
			case 1:
			case 2:
				$view = 'take_planned';
				break;
			case 3:
				$participants = $this->TestTakesService->getParticipants($take_id);
				$test_take = $this->TestTakesService->getTestTake($take_id);

				if($clean && $participant_status == 3)
				{
					$this->check_for_login();
				}

				if(!$this->Session->check('take_question_index')) {
					$take_question_index = 0;
					$this->Session->write('take_question_index', 0);
				}else{
					$take_question_index = $this->Session->read('take_question_index');
				}

				if($question_index != null) {
					$take_question_index = $question_index;
					$this->Session->write('take_question_index', $question_index);
				}

				$questions = $this->TestTakesService->getParticipantQuestions($participant_id);

				$this->set('questions', $questions);
				$this->set('take_question_index', $take_question_index);
				$this->set('take_id', $take_id);

				if(isset($questions[$take_question_index]['question_id'])) {
					$this->set('active_question', $questions[$take_question_index]['question_id']);
				}else{
					$this->set('active_question', $questions[0]['question_id']);
				}

				$this->Session->write('has_next_question', isset($questions[$take_question_index + 1]));

				$view = 'take_active';
				break;

			case 4:
			case 5:
			case 6:
				$this->Session->delete('drawing_pad');
				$this->Session->delete('drawing_data');
				$this->Session->delete('take_question_index');
				$this->Session->delete('active_question');

				$view = 'take_taken';
				break;
		}

		$this->set('take', $take);
		$this->set('take_id', $take_id);
		
		$this->render($view, 'ajax');
	}

	public function take_answer_overview($take_id) {
		$this->Session->write('take_id', $take_id);
		$take = $this->TestTakesService->getTestTake($take_id);

		$participant_id = $take['test_participant']['id'];

		$questions = $this->TestTakesService->getParticipantQuestions($participant_id);

		$participant_status = $take['test_participant']['test_take_status_id'];

		if(in_array($participant_status, [4, 5, 6])) {
			$this->render('take_taken', 'ajax');
		}

		$this->set('participant_id', $participant_id);
		$this->set('questions', $questions);
		$this->set('take_id', $take_id);
	}

	public function heart_beat() {
		$this->autoRender = false;
		$participant_id = $this->Session->read('participant_id');
		$take_id = $this->Session->read('take_id');

		$question_id = null;

		if($this->Session->check('answer_id')) {
			$answer_id = $this->Session->read('answer_id');
		}else{
			$answer_id = null;
		}

		$response = $this->TestTakesService->heartBeat($take_id, $participant_id, $answer_id);

		if(in_array($response['test_take_status']['id'], [4, 6]) && $response['test_take']['test_take_status_id'] == 7) {
			$this->TestTakesService->startParticpantDiscussing($take_id, $participant_id);
		}

		echo json_encode([
			'take_status' => $response['test_take']['test_take_status_id'],
			'participant_status' => $response['test_take_status']['id'],
			'discussing_question_id' => $response['test_take']['discussing_question_id'],
			'alert' => $response['alert'] ? 1 : 0

		]);
	}

	public function planned_teacher() {
		$periods = $this->TestsService->getPeriods();
		$periods = [0 => 'Alle'] + $periods;
		$this->set('periods', $periods);
	}

	public function planned_student() {

	}

	public function taken_student() {

	}

	public function discussed_glance() {

	}

	public function rated_student() {

	}

	public function glance($take_id, $question_index = 0) {
		$this->Session->write('take_id', $take_id);

		$take = $this->TestTakesService->getTestTake($take_id);

		if(empty($take['show_results']) || strtotime($take['show_results']) < time()) {
			die('Deze toets is niet meer in te zien');
		}

		$questions = $this->TestTakesService->getParticipantQuestions($take['test_participant']['id']);
		$answer = $this->TestTakesService->getParticipantQuestionAnswer($take['test_participant']['id'], $questions[$question_index]['question_id']);

		if(isset($questions[$question_index]['answer_parent_questions'][0]['group_question_id'])) {
			$group = $questions[$question_index]['answer_parent_questions'][0]['group_question_id'];
		}else{
			$group = "";
		}

		$this->set('group', $group);
		$this->set('answer', $answer);
		$this->set('question_index', $question_index);
		$this->set('questions', $questions);
		$this->set('take', $take);
		$this->set('take_id', $take_id);
	}

	public function glance_answer($take_id, $question_index = 0) {
		$take = $this->TestTakesService->getTestTake($take_id);
		$questions = $this->TestTakesService->getParticipantQuestions($take['test_participant']['id']);
		$answer = $this->TestTakesService->getParticipantQuestionAnswer($take['test_participant']['id'], $questions[$question_index]['question_id']);

		$answer['answer'] = $answer[0];

		switch($answer['answer']['question']['type']) {
			case "OpenQuestion":
				$view = 'rate_open';
				break;

			case "CompletionQuestion":
				if($answer['answer']['question']['subtype'] == 'completion') {
					$view = 'rate_completion';
				}elseif($answer['answer']['question']['subtype'] == 'multi') {
					$view = 'rate_multi_completion';
				}
				break;

			case "MatchingQuestion":
				if($answer['answer']['question']['subtype'] == 'Matching') {
					$view = 'rate_matching';
				}elseif($answer['answer']['question']['subtype'] == 'Classify') {
					$view = 'rate_classify';
				}
				break;

			case "MultipleChoiceQuestion":
				if($answer['answer']['question']['subtype'] == 'MultiChoice' || $answer['answer']['question']['subtype'] == 'MultipleChoice') {
					$view = 'rate_multiple_choice';
				}elseif($answer['answer']['question']['subtype'] == 'TrueFalse') {
					$view = 'rate_true_false';
				}elseif($answer['answer']['question']['subtype'] == 'ARQ') {
					$view = 'rate_arq';
				}
				break;

			case "RankingQuestion":
				$view = 'rate_ranking';
				break;

			case "DrawingQuestion":
				$view = 'rate_drawing';
				break;

			default:
				die;
				break;
		}

		if(empty($answer['answer']['json'])) {
			$view = 'rate_empty';
		}

		$this->set('rating', $answer);

		$this->render($view, 'ajax');
	}

	public function test_takes($take_id) {

	}

	public function load_discussed_glance() {
		$params = $this->request->data;

		$params['filter'] = [
			'test_take_status_id' => [8,9],
			'show_results_from' => date('Y-m-d H:i:00')
		];
		$params['order']['time_start'] = 'desc';

		$params['with'] = ['ownTestParticipant'];

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function taken_teacher() {
		$periods = $this->TestsService->getPeriods();
		$periods = [0 => 'Alle'] + $periods;
		$this->set('periods', $periods);
	}

	public function widget_planned() {
		$params = $this->request->data;

		$params['filter'] = [
			'test_take_status_id' => [1, 3]
		];
		$params['order'] = [
			'time_start' => 'asc'
		];

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function widget_rated() {
		$params = $this->request->data;

		$params['filter'] = [
			'test_take_status_id' => 9,
		];
		$params['with'] = ['ownTestParticipant'];
		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function load_planned_student() {
		$params = $this->request->data;

		$params['filter'] = [
			'test_take_status_id' => [1, 3]
		];

		$params['order']['time_start'] = 'asc';

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function load_planned_teacher() {

		$params = $this->request->data;

		$params['order']['time_start'] = 'asc';

		$filters = array();
		parse_str($params['filters'], $filters);

		$filters = $filters['data']['TestTake'];

		$params['filter'] = [
			'test_take_status_id' => 1
		];

		if($filters['retake'] != -1) {
			$params['filter']['retake'] = $filters['retake'];
		}

		if(!empty($filters['period_id'])) {
			$params['filter']['period_id'] = $filters['period_id'];
		}

		if(!empty($filters['time_start_from'])) {
			$params['filter']['time_start_from'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_from']));
		}

		if(!empty($filters['time_start_to'])) {
			$params['filter']['time_start_to'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_to']));
		}

		$user_id = AuthComponent::user()['id'];

		$params['filter']['invigilator_id'] = $user_id;

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function load_taken_teacher() {

		$params = $this->request->data;

		$params['order']['time_start'] = 'desc';

		$filters = array();
		parse_str($params['filters'], $filters);

		$filters = $filters['data']['TestTake'];

		$params['filter'] = [
			'test_take_status_id' => 6,
			'user_id' => AuthComponent::user('id')
		];

		if($filters['retake'] != -1) {
			$params['filter']['retake'] = $filters['retake'];
		}

		if(!empty($filters['period_id'])) {
			$params['filter']['period_id'] = $filters['period_id'];
		}

		if(!empty($filters['time_start_from'])) {
			$params['filter']['time_start_from'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_from']));
		}

		if(!empty($filters['time_start_to'])) {
			$params['filter']['time_start_to'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_to']));
		}

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function load_taken_student() {

		$params = $this->request->data;

		$params['filter'] = [
			'test_take_status_id' => [6, 7]
		];
		$params['order']['time_start'] = 'desc';

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function load_rated_student() {

		$params = $this->request->data;

		$params['filter'] = [
			'test_take_status_id' => 9,
		];
		$params['order']['time_start'] = 'desc';
		$params['with'] = ['ownTestParticipant'];
		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}


	public function select_test() {

	}

	public function select_test_retake() {

	}

	public function lost_focus() {

		$this->autoRender = false;
		$take_id = $this->Session->read('take_id');
		$participant_id = $this->Session->read('participant_id');

		$this->TestTakesService->lostFocus($take_id, $participant_id);
	}

	public function screenshot_detected() {
		$this->autoRender = false;
		$take_id = $this->Session->read('take_id');
		$participant_id = $this->Session->read('participant_id');

		$this->TestTakesService->screenshotDetected($take_id, $participant_id);
	}

	public function check_for_login() {
		$this->autoRender = false;
		$take_id = $this->Session->read('take_id');
		$participant_id = $this->Session->read('participant_id');

		$this->TestTakesService->CheckForLogin($take_id, $participant_id);
	}

	public function participant_notes($take_id, $participant_id) {
		if($this->request->is('post')) {
			$this->autoRender = false;
			$this->TestTakesService->setParticipantNote($take_id, $participant_id, $this->request->data['note']);
		}else {

			$participant = $this->TestTakesService->getParticipant($take_id, $participant_id);

			$this->set('take_id', $take_id);
			$this->set('participant_id', $participant_id);
			$this->set('participant', $participant);
		}
	}

	public function confirm_event($take_id, $event_id) {

		$this->autoRender = false;
		$this->TestTakesService->removeEvent($take_id, $event_id);
	}

	public function events($take_id, $participant_id) {
		$events = $this->TestTakesService->getEvents($take_id, $participant_id);

		$this->set('events', $events);
		$this->set('participant_id', $participant_id);
	}

	public function load_to_rate() {
		$params = $this->request->data;

		$params['order']['time_start'] = 'desc';
		$params['with'] = ['participantStatus'];

		$filters = array();
		parse_str($params['filters'], $filters);

		$filters = $filters['data']['TestTake'];

		$params['filter'] = [
			'test_take_status_id' => 8,
			'user_id' => AuthComponent::user('id')
		];

		if($filters['retake'] != -1) {
			$params['filter']['retake'] = $filters['retake'];
		}

		if(!empty($filters['period_id'])) {
			$params['filter']['period_id'] = $filters['period_id'];
		}

		if(!empty($filters['time_start_from'])) {
			$params['filter']['time_start_from'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_from']));
		}

		if(!empty($filters['time_start_to'])) {
			$params['filter']['time_start_to'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_to']));
		}

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function load_rated() {
		$params = $this->request->data;

		$params['order']['time_start'] = 'desc';

		$filters = array();
		parse_str($params['filters'], $filters);

		$filters = $filters['data']['TestTake'];

		if($filters['retake'] != -1) {
			$params['filter']['retake'] = $filters['retake'];
		}

		if(!empty($filters['period_id'])) {
			$params['filter']['period_id'] = $filters['period_id'];
		}

		if(!empty($filters['time_start_from'])) {
			$params['filter']['time_start_from'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_from']));
		}

		if(!empty($filters['time_start_to'])) {
			$params['filter']['time_start_to'] = date('Y-m-d 00:00:00', strtotime($filters['time_start_to']));
		}

		$params['filter']['test_take_status_id'] = 9;
		$params['filter']['user_id'] = AuthComponent::user('id');

		$params['order']['time_start'] = 'desc';
		$params['with'] = ['participantStatus'];

		$test_takes = $this->TestTakesService->getTestTakes($params);

		$this->set('test_takes', $test_takes['data']);
	}

	public function surveillance() {
		$user_id = AuthComponent::user()['id'];

		$params['filter']['invigilator_id'] = $user_id;
		$params['filter']['test_take_status_id'] = 3;
		$params['mode'] = 'list';

		$takes = $this->TestTakesService->getTestTakes($params);

		$newArray = [];

		foreach($takes as $take_id => $take) {
			$take['info'] = $this->TestTakesService->getTestTakeInfo($take_id);
			$newArray[$take_id] = $take;
		}

		$takes = $newArray;
		$this->set('takes', $takes);
	}

	public function surveillance_data() {
		$this->autoRender = false;
		$user_id = AuthComponent::user()['id'];

		$params['filter']['invigilator_id'] = $user_id;
		$params['filter']['test_take_status_id'] = 3;
		$params['mode'] = 'list';

		$takes = $this->TestTakesService->getTestTakes($params);

		$response = [
			'takes' => [],
			'participants' => [],
			'time' => date('H:i'),
			'alerts' => 0,
			'ipAlerts' => 0
		];

		foreach($takes as $take_id => $take) {
			$take['info'] = $this->TestTakesService->getTestTakeInfo($take_id);

			foreach ($take['info']['school_classes'] as $class) {

				if ($class['max_score'] > 0) {
					$percentage = round((100 / $class['max_score']) * $class['made_score']);
				} else {
					$percentage = 0;
				}

				$response['takes']['progress_' . $take['info']['id'] . '_' . $class['id']] = $percentage;
			}
			
			foreach($take['info']['test_participants'] as $participant) {

				if ($participant['alert'] == true) {
					$response['alerts']++;
				}
				if(!$participant['ip_correct']) {
					$response['ipAlerts']++;
				}

				switch ($participant['test_take_status']['id']) {
					case 1:
						$label = 'info';
						$text = 'Ingepland';
						break;

					case 2:
						$label = 'danger';
						$text = 'Niet gemaakt';
						break;

					case 3:
						$label = 'success';
						$text = 'Maakt toets';
						break;

					case 4:
						$label = 'info';
						$text = 'Ingeleverd';
						break;

					case 5:
						$label = 'warning';
						$text = 'Ingeleverd (geforceerd)';
						break;

					case 6:
						$label = 'success';
						$text = 'Ingenomen';
						break;
				}

				$percentage = round((100 / $participant['max_score']) * $participant['made_score']);
				
				$response['participants'][$participant['id']] = [
					'percentage' => $percentage,
					'label' => $label,
					'text' => $text,
					'alert' => $participant['alert'],
					'ip' => $participant['ip_correct'],
					'status' => $participant['test_take_status']['id']
				];
			}
		}

		return json_encode($response);
	}

	public function to_rate() {
		$periods = $this->TestsService->getPeriods();
		$periods = [0 => 'Alle'] + $periods;
		$this->set('periods', $periods);
	}

	public function rated() {
		$periods = $this->TestsService->getPeriods();
		$periods = [0 => 'Alle'] + $periods;
		$this->set('periods', $periods);
	}


	public function discussion() {
		$user_id = AuthComponent::user()['id'];

		$params['filter']['user_id'] = $user_id;
		$params['filter']['test_take_status_id'] = 7;
		$params['mode'] = 'list';

		$takes = $this->TestTakesService->getTestTakes($params);

		if(count($takes) > 0) {

			$take = $this->TestTakesService->getTestTake(key($takes));
			if(!empty($take['discussing_parent_questions'])) {
				//$group = $this->QuestionsService->getSingleQuestion();
				$group = $take['discussing_parent_questions'][0]['group_question_id'];
				$this->set('group', $group);
			}


			$this->set('has_next_question', $this->Session->read('has_next_question'));
			$this->set('take', $take);
		}
	}

	public function discussion_participants($take_id) {
		$take = $this->TestTakesService->getTestTakeInfo($take_id);
		$this->set('take', $take);
	}

	public function start_discussion_popup($take_id) {
		$this->set('take_id', $take_id);
	}

	public function start_rate_popup($take_id) {
		$this->set('take_id', $take_id);
	}

	public function next_discussion_question($take_id) {
		$this->autoRender = false;
		$response = $this->TestTakesService->nextDiscussionQuestion($take_id);
		$this->Session->write('has_next_question', $response['has_next_question']);
	}

	public function start_test($take_id) {
		$this->autoRender = false;
		$response = $this->TestTakesService->startTest($take_id);
	}

	public function start_discussion($take_id, $type) {
		$this->autoRender = false;
		$response = $this->TestTakesService->startDiscussion($take_id, $type);

		if($response['discussing_question_id'] == null) {
			$response = $this->TestTakesService->nextDiscussionQuestion($take_id);
			$this->Session->write('has_next_question', $response['has_next_question']);
		}
	}

	public function update_show_results($take_id) {

		if($this->request->is('post') || $this->request->is('put')) {

			$data = $this->request->data['TestTake'];

			$this->TestTakesService->updateShowResults($take_id, $data['active'] == '1', $data['show_results']);

			$this->formResponse(
				true,
				[]
			);

			die;
		}

		$this->set('take', $this->TestTakesService->getTestTake($take_id));
		$this->render('update_show_results', 'ajax');
	}

	public function finish_discussion($take_id) {
		$this->autoRender = false;
		$response = $this->TestTakesService->finishDiscussion($take_id);
	}

	public function delete($take_id) {
		$this->autoRender = false;
		$this->TestTakesService->deleteTestTake($take_id);
	}

	public function rates_preview($take_id) {
		$this->set('take_id', $take_id);
	}

	public function rates_pdf_container($take_id) {
		$this->set('take_id', $take_id);
        $this->set("attachment_url", "/test_takes/rates_pdf/" . $take_id);
        $this->set("is_question_pdf", true);
        $this->render('/Pdf/pdf_container', 'ajax');
	}

	public function rates_pdf($take_id) {

		$take = $this->TestTakesService->getTestTake($take_id);

		$params['with'] = ['statistics'];

		$participants = $this->TestTakesService->getParticipants($take_id, $params);

		$view = new View($this, false);

		$view->set('participants', $participants);
		$view->set('take', $take);

		$html = $view->render('rates_pdf', 'pdf');

		$this->response->body(HtmlConverter::htmlToPdf($html, 'portrait'));
		$this->response->type('pdf');

		return $this->response;
	}

	public function answers_preview($take_id) {
		$this->set('take_id', $take_id);
	}

	public function answers_pdf_container($take_id) {
		$this->set('take_id', $take_id);
        $this->set("attachment_url", "/test_takes/answers_pdf/" . $take_id);
        $this->set("is_question_pdf", true);
        $this->render('/Pdf/pdf_container', 'ajax');
	}

	public function answers_pdf($take_id) {
		$test_take = $this->TestTakesService->getTestTakeAnswers($take_id);
		$allQuestions = $this->TestsService->getQuestions($test_take['test']['id']);

		$questions = [];

		foreach ($allQuestions as $allQuestion) {

			if ($allQuestion['question']['type'] == 'GroupQuestion') {
				foreach($allQuestion['question']['group_question_questions'] as $item) {
					$item['question_group']['text'] = $allQuestion['question']['question'];
					$questions[] = $item;
				}
			} else {
				$questions[] = $allQuestion;
			}
		}

		$newArray = [];

		foreach($questions as $question) {
			$newArray[$question['id']] = $question;
		}

		$participants = $this->TestTakesService->getParticipants($take_id);

		$view = new View($this, false);
		$view->set('test_take', $test_take);
		$view->set('questions', $newArray);
		$view->set('participants', $participants);

		$html = $view->render('answers_pdf', 'pdf');

		$this->response->body(HtmlConverter::htmlToPdf($html, 'portrait'));
		$this->response->type('pdf');

		return $this->response;
	}

	public function select_test_take_list() {

		$params = $this->request->data;

		$test_takes = $this->TestTakesService->getTestTakes($params);
		$this->set('test_takes', $test_takes['data']);
	}

	public function add_class_participants($class_id) {

		if($this->request->is('post')) {
			$this->autoRender = false;
			$students = $this->request->data['Student'];

			$addArray = [];

			foreach($students as $id => $checked) {

				if($checked == 1) {
					$addArray[] = $id;
				}
			}

			$take_id = $this->Session->read('take_id');

			$this->TestTakesService->addClassStudents($take_id, $class_id, $addArray);
		}

		$students = $this->TestTakesService->getClassStudents($class_id);
		$this->set('students', $students);
		$this->set('class_id', $class_id);
	}

	public function select_test_list() {
		$education_levels = $this->TestsService->getEducationLevels(true, false);
		$periods = $this->TestsService->getPeriods();
		$subjects = $this->TestsService->getSubjects();
		$kinds = $this->TestsService->getKinds();

		$this->set('education_levels', $education_levels);
		$this->set('kinds', $kinds);
		$this->set('periods', $periods);
		$this->set('subjects', $subjects);

		$tests = $this->TestsService->getTests($this->request->data);
		$this->set('tests', $tests['data']);
	}

	public function get_header_session(){
		// exit(json_encode($this->Session->read('headers')));
		exit($this->Session->read("TLCHeader"));
	}


	public function export_to_rtti($take_id){

		require __DIR__.'/rtti_api/autoload.php';
		require __DIR__.'/rtti_api/nusoap.php';

		// Predefine variables for this little section..
		$errors 	  = array();
		$toetsAfnames = array();
		$params 	  = array();
		
		// This is the WSDL we are going to send a request to.
		// https://www.rttionline.nl/RTTIToetsService.Test/RTTIToetsService.svc?WSDL

		// Fetch al the information we need to create a decent RTTI request...
		$test_take      = $this->TestTakesService->getTestTakeAnswers($take_id);
		$testTakeInfo   = $this->TestTakesService->getTestTakeInfo($take_id);
		$participants   = $this->TestTakesService->getParticipants($take_id);
		$allQuestions   = $this->TestsService->getQuestions($test_take['test_id']);
		$schoolLocation = $this->SchoolLocationsService->getSchoolLocation($testTakeInfo['school_location_id']);

		$date = new DateTime($testTakeInfo['time_start']);

		$testCode = $testTakeInfo['test']['name'].'|'.$testTakeInfo['test']['subject']['abbreviation'].'|'.$testTakeInfo['school_classes'][0]['name'].'|'.$testTakeInfo['test_id'];

		try{
			// START SETTING DATA FOR SCHOOL SECTION
			if($schoolLocation['data'][0]['school_id'] == NULL && $schoolLocation['data'][0]['external_main_code'] == NULL)
				$errors[] = 'Deze school locatie heeft geen overkoepelende school, en geen brincode, niet exporteerbaar.';

			if($schoolLocation['data'][0]['external_main_code'] == NULL) {
				$external_main_code = $schoolLocation['data'][0]['school']['external_main_code'];
			}else{
				$external_main_code = $schoolLocation['data'][0]['external_main_code'];
			}

			if($external_main_code == NULL && $schoolLocation['data'][0]['school']['umbrella_organisation_id'] == NULL)
				$errors[] = 'Deze school heeft geen brincode, en geen overkoepelende organisatie, niet exporteerbaar.';

			if($external_main_code == NULL)
				$external_main_code = $schoolLocation['data'][0]['school']['umbrella_organisation']['external_main_code'];

			if($external_main_code == NULL)
				$errors[] = 'Geen brincode gevonden voor deze setup, neem contact op met administrators.';

			$ctpSchool = new ctpSchool(new DateTime('now'));
			$ctpSchool->setDependancecode($schoolLocation['data'][0]['external_sub_code']);
			$ctpSchool->setBrincode($external_main_code);

			$yearinfo = $this->SchoolYearsService->getSchoolYear($testTakeInfo['school_classes']['school_year_id'])["data"][0];

			$nextYear = (int)$yearinfo['year']+1;
			$year = (string) $yearinfo['year'] . '-' . (string) $nextYear;

			$debug = $yearinfo;

			$year = "2018-2019";	
			$this->log("MARKO: Verwijder deze hack!".$year, 'error');


			$date = new DateTime('now');

			$params['school'] = array(
				'aanmaakdatum' => $date->format(\DateTime::ATOM),
				'dependancecode' => $schoolLocation['data'][0]['external_sub_code'],
				'brincode' => $external_main_code,
				'schooljaar' => $year,
			);
			$params['toetsafnames'] = array();
			foreach($participants as $participant) {
				
				$resultaten = array();
				$resArray = array();

				$af = array();

				foreach($this->TestTakesService->getParticipantQuestions($participant['id']) as $question) {

					$answer = $this->TestTakesService->getParticipantQuestionAnswer($participant['id'], $question['question_id']);

					$resArray['resultaat'][] = array(
						'key' => $answer[0]['id'],
						'afnamedatum' => $date->format('Y-m-d'),
						'toetscode' => $testCode,
						'toetsonderdeelcode' => $question['question_id'],
						'score' => $answer[0]['final_rating']
					);
				}
				if(empty($resArray)) continue;

				$af = array(
					'leerlingid' => $participant['user']['external_id'],
					'resultaatverwerkerid' => $testTakeInfo['user']['external_id'],
					'resultaten' => $resArray
				);
				$params['toetsafnames']['toetsafname'][] = $af;
			}

			$i = 1;
			$toArray = array();

			foreach($allQuestions as $question) {

				if ($question['question']['type'] == 'GroupQuestion') {

					foreach($question['question']['group_question_questions'] as $item) {
						if($item['question']['score'] == NULL) {
							$score = 0;
						} else {
							$score = $item['question']['score'];
						}

						$toArray['toetsonderdeel'][] = array(
							'toetsonderdeelvolgnummer' => $i,
							'toetsonderdeelcode' => array('!' => $item['question_id']),
							'toetsonderdeelnormering' => array(
								'toetsniveau' => array('!' => $item['question']['rtti']),
								'norm' => array(
									'eindnormwaarde' => $score
								)
							)
						);

						$i++;
					}
				} else {
					if($question['question']['score'] == NULL) {
						$score = 0;
					} else {
						$score = $question['question']['score'];
					}

					$toArray['toetsonderdeel'][] = array(
						'toetsonderdeelvolgnummer' => $i,
						'toetsonderdeelcode' => array('!' => $question['question_id']),
						'toetsonderdeelnormering' => array(
							'toetsniveau' => array('!' => $question['question']['rtti']),
							'norm' => array(
								'eindnormwaarde' => $score
							)
						)
					);

					$i++;
				}
			}

			$params['toetsen'] = array();
			$params['toetsen']['toets']['toetscode'] = array(
				'!' => $testCode,
			);
			$params['toetsen']['toets']['toetsonderdelen'] = $toArray;

		} catch (\Exception $e) {
			$errors[] = $e->getMessage();
		}

		if(empty($errors)) {
			try {
				$auth['aut:autorisatie'] = array(
					'autorisatiesleutel' => Configure::read('RTTI.autorisatiesleutel'),
					'klantcode' => Configure::read('RTTI.klantcode'),
					'klantnaam' => Configure::read('RTTI.klantnaam')
				);

				$client = new nusoap_client(
                    Configure::read('RTTI.wsdl_url'),
					true
				);
				$client->soap_defencoding = 'UTF-8';

				$result = $client->call(
					'BrengLeerresultaten',
					array(
						'leerresultaten_verzoek' => array(
							'school' => $params['school'],
							'toetsafnames' => $params['toetsafnames'],
							'toetsen' => $params['toetsen']
						)
					),
					'http://www.edustandaard.nl/leerresultaten/2/leerresultaten',
					'leer:leerresultaten_verzoek',
					$auth
				);

				if(Configure::read('RTTI.debug')) {
                    $this->log("RTTI request was: " . $client->request, 'debug');
                    $this->log("RTTI response was: " . $client->response, 'debug');
                    $this->log("RTTI error was: " . $client->getError(), 'debug');
                }


$this->log(htmlspecialchars($client->request, ENT_QUOTES), 'error');
	$this->log($result, 'error');
   // Check for errors
   $err = $client->getError();
   if ($err) {
	$this->log($err, 'error');
      // Display the error
}


			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}

			if(!$this->TestTakesService->updateExportedToRtti($take_id)){
				$errors[] = 'Could not update exported to rtti date';
			}

			$this->set('success','verzonden naar RTTI.');
		}

		$this->set('errors',$errors);
		$this->set('debug',$debug);

		$this->formResponse(
			empty($errors),
			$debug
		);
	}
}
