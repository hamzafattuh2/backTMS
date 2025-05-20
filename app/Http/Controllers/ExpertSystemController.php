<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpertSystemController extends Controller
{
    public function process(Request $request)
    {
        $step = $request->input('step');
        $answers = $request->input('answers', []);

        switch ($step) {
            case 1:
                return response()->json([
                    'question' => 'Do you like to go inside Damascus or Syrian governorates?',
                    'options' => ['inside', 'outside'],
                    'next_step' => 2
                ]);

            case 2:
                if (!isset($answers['inside_outside'])) {
                    return $this->error('Missing answer: inside_outside');
                }

                return response()->json([
                    'question' => 'Do you like nature?',
                    'options' => ['yes', 'no'],
                    'next_step' => 3
                ]);

            case 3:
                if (!isset($answers['nature'])) {
                    return $this->error('Missing answer: nature');
                }

                if ($answers['inside_outside'] == 'inside') {
                    if ($answers['nature'] == 'yes') {
                        return response()->json([
                            'question' => 'Do you prefer mountains or parks?',
                            'options' => ['mountain', 'park'],
                            'next_step' => 4
                        ]);
                    } else {
                        return response()->json([
                            'question' => 'Do you like heritage places?',
                            'options' => ['yes', 'no'],
                            'next_step' => 5
                        ]);
                    }
                } else { // outside
                    return response()->json([
                        'question' => 'Do you prefer summer or winter tourism?',
                        'options' => ['summer', 'winter'],
                        'next_step' => 6
                    ]);
                }

            case 4: // Inside Damascus + Nature = mountain or park
                if (!isset($answers['mountain_park'])) {
                    return $this->error('Missing answer: mountain_park');
                }

                if ($answers['mountain_park'] == 'mountain') {
                    return $this->finalResult('I advise you to go to Mount Qasioun.');
                } else {
                    return $this->finalResult('I advise you to go to Tishreen Park.');
                }

            case 5: // Inside Damascus + No Nature => heritage or not
                if (!isset($answers['heritage'])) {
                    return $this->error('Missing answer: heritage');
                }

                if ($answers['heritage'] == 'yes') {
                    return $this->finalResult('I advise you to go to Old Damascus.');
                } else {
                    return $this->finalResult('I advise you to go to City Center Mall.');
                }

            case 6: // Outside Damascus
                if (!isset($answers['summer_winter'])) {
                    return $this->error('Missing answer: summer_winter');
                }

                if ($answers['summer_winter'] == 'summer') {
                    return response()->json([
                        'question' => 'Do you prefer swimming or forests?',
                        'options' => ['swimming', 'forests'],
                        'next_step' => 7
                    ]);
                } else {
                    return $this->finalResult('I advise you to go to Bloudan or Zabadani.');
                }

            case 7: // Summer tourism
                if (!isset($answers['swimming_forests'])) {
                    return $this->error('Missing answer: swimming_forests');
                }

                if ($answers['swimming_forests'] == 'swimming') {
                    return $this->finalResult('I advise you to go to Latakia or Tartous.');
                } else {
                    return $this->finalResult('I advise you to go to Kassab or Mashqita.');
                }

            default:
                return $this->error('Invalid step.');
        }
    }

    private function finalResult($message)
    {
        return response()->json([
            'result' => $message
        ]);
    }

    private function error($message)
    {
        return response()->json([
            'error' => $message
        ], 400);
    }
}
