<?php

namespace App\Policies;

use App\User;
use App\Post_comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post_comment.
     *
     * @param  \App\User  $user
     * @param  \App\Post_comment  $postComment
     * @return mixed
     */
    public function view(User $user, Post_comment $postComment)
    {
        //
    }

    /**
     * Determine whether the user can create post_comments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the post_comment.
     *
     * @param  \App\User  $user
     * @param  \App\Post_comment  $postComment
     * @return mixed
     */
    public function update(User $user, Post_comment $postComment)
    {
        //
    }

    /**
     * Determine whether the user can delete the post_comment.
     *
     * @param  \App\User  $user
     * @param  \App\Post_comment  $postComment
     * @return mixed
     */
    public function delete(User $user, Post_comment $postComment)
    {
        //
        return $user->id === $postComment->user_id;
    }

    /**
     * Determine whether the user can restore the post_comment.
     *
     * @param  \App\User  $user
     * @param  \App\Post_comment  $postComment
     * @return mixed
     */
    public function restore(User $user, Post_comment $postComment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the post_comment.
     *
     * @param  \App\User  $user
     * @param  \App\Post_comment  $postComment
     * @return mixed
     */
    public function forceDelete(User $user, Post_comment $postComment)
    {
        //
    }
}
