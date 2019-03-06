<?php
namespace App\Http\Controllers;
use App\Http\Resources\Feedback as FeedbackResource;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FeedbackController extends Controller
{
    /**
     * @param $id
     * @return FeedbackResource
     */
    public function show($id)
    {
        return new FeedbackResource(Feedback::find($id));
    }

    public function display()
    {
        return Feedback::orderBy('helpful', 'desc')->get();
    }


    public function helpful(Request $request, $helpType)
    {
        $feedback_to_update = $this->feedbackExists($request->critic_name, $request->movie_name);

        if(empty($feedback_to_update))
        {
            $feedback = $request->all();
            if($helpType == 'helpful'){
                $feedback['helpful'] = 1;
                $feedback['unhelpful'] = 0;
            }else {
                $feedback['helpful'] = 0;
                $feedback['unhelpful'] = 1;
            }
            $fb = Feedback::create($feedback);
            $fb->save();
        }else{
            $feedback_to_update->increment($helpType);
            $feedback_to_update->save();
        }
        return response('', 204);
    }


    public function feedbackExists($critic_name, $movie_name)
    {
        $feedback_to_update = Feedback::where('critic_name', $critic_name)
            ->where('movie_name', $movie_name)
            ->first();
        return (empty($feedback_to_update)? false : $feedback_to_update );
    }
}