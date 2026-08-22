<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemoController extends Controller
{
    /**
     * Display the memo list view with authenticated user's memos and selected memo details.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        // Fetch all memos belonging to the authenticated user, ordered by most recently updated
        $memos = Memo::where('user_id', Auth::id())
                     ->orderBy('updated_at', 'desc')
                     ->get();

        // Check if an 'id' parameter exists in the URL request
        $select_memo = null;
        if ($request->has('id')) {
            // Find the specific memo belonging to the logged-in user
            $select_memo = Memo::where('user_id', Auth::id())
                               ->where('id', $request->id)
                               ->first();
        }

        // Pass both memos collection and selected memo to the view
        return view('memo', compact('memos', 'select_memo'));
    }

    /**
     * Store a new default memo in the database.
     *
     * @return RedirectResponse
     */
    public function add()
    {
        // Create a new default memo entry for the authenticated user
        $memo = Memo::create([
            'user_id' => Auth::id(),
            'title'   => 'New Memo',
            'content' => '',
        ]);

        // Redirect back to the memo index view with the newly created memo selected
        return redirect()->route('memo.index', ['id' => $memo->id]);
    }

    /**
     * Update an existing memo in the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        // Find the target memo belonging to the authenticated user
        $memo = Memo::where('user_id', Auth::id())
                    ->where('id', $request->edit_id)
                    ->first();

        // Update title and content if the memo exists
        if ($memo) {
            $memo->title = $request->edit_title;
            $memo->content = $request->edit_content;
            $memo->save();
        }

        // Redirect back to the memo index view with the updated memo selected
        return redirect()->route('memo.index', ['id' => $request->edit_id]);
    }

    /**
     * Delete a memo from the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        // Find and delete the memo matching the user_id and edit_id
        Memo::where('user_id', Auth::id())
            ->where('id', $request->edit_id)
            ->delete();

        // Redirect back to the main memo index page
        return redirect()->route('memo.index');
    }
}