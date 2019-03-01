<?php
namespace App\Http\Controllers;
use App\Http\Resources\Feedback as FeedbackResource;
use App\Models\Feedback;
use Illuminate\Http\Request;


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

    public function store(Request $request)
    {
        $feedback = $request->all();
        $fb = Feedback::create($feedback);
        $fb->save();
        return response('', 204);
    }

}