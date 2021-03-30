<?php


trait CarouselMethods
{
    private function setNotificationsForViewGroup($groupQuestion){
        if($groupQuestion['groupquestion_type']!='carousel'){
            return;
        }
        $this->setNotificationsForViewGroupScore($groupQuestion);
        $this->setNotificationsForViewGroupNumber($groupQuestion);   
    }

    private function setNotificationsForViewGroupScore($groupQuestion){
        $arr = array_map(function($question){
                                                return $question['question']['score'];
                                            }, 
                                            $groupQuestion['group_question_questions']
                        );
        if(count(array_unique($arr)) <= 1){
            return;
        }
        $this->set('carouselGroupQuestionNotify', true);
        $msg = sprintf('<p>Let op: de vragen in de carrousel vraaggroep `%s` hebben verschillende punten. Na de toetsafname kan hierdoor geen score/cijfer berekend worden. Pas de punten voor elke vraag in deze vraaggroep aan, zodat ze hetzelfde zijn.</p>',$groupQuestion['name']);
        $this->setCarouselGroupQuestionNotifyMsg($msg);
    }

    private function setNotificationsForViewGroupNumber($groupQuestion){
        $number = count($groupQuestion['group_question_questions']);
        if($number>=$groupQuestion['number_of_subquestions']){
            return;
        }
        $this->set('carouselGroupQuestionNotify', true);
        $msg = sprintf('<p>Let op: Er zijn te weinig vragen in vraagcarrousel `%s` om deze toets te gebruiken. Vul het aantal vragen aan of pas het aantal te vragen toetsvragen aan.</p>',$groupQuestion['name']);
        $this->setCarouselGroupQuestionNotifyMsg($msg);
    }

    private function setCarouselGroupQuestionNotifyMsg($msg)
    {
        $this->carouselGroupQuestionNotifyMsg = $this->carouselGroupQuestionNotifyMsg.' '.$msg;
        $this->set('carouselGroupQuestionNotifyMsg', $this->carouselGroupQuestionNotifyMsg);

    }
}