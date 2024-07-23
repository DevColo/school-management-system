<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Completions\CreateResponse;
use Gemini;

class ResearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat(){
        $userMessage = 'who is the president of Liberia 2024?';

        // $result = OpenAI::chat()->create([
        //     'model' => 'gpt-4o',
        //     'messages' => [
        //         ['role' => 'user', 'content' => 'Hello!'],
        //     ],
        // ]);
        

        //echo $result->choices[0]->message->content; 
        $yourApiKey = getenv('GEMINI_API_KEY');
        $client = Gemini::client($yourApiKey);

        $result = $client->geminiPro()->generateContent($userMessage);

        $result = $result->text();
        dd($result);die;
    }
}
