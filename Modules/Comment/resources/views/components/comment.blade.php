<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <section class="bg-dark dark:bg-gray-900 py-8 lg:py-16 antialiased">
    <div class="max-w-2xl mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg lg:text-2xl font-bold text-white-900 dark:text-white">Comments</h2>
        </div>
        <form class="mb-6" method="post" action="{{route('comment.add')}}">
            @csrf           
            <div
                class="py-2 px-4 mb-4 bg-dark rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <label for="comment" class="sr-only">Your comment</label>
                <textarea id="comment" rows="6" name="comment"
                    class="px-0 w-full text-sm text-white-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                    placeholder="Write a comment..." required></textarea>
            </div>
            <button type="submit" id="upload-btn"
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                Post comment
            </button>
        </form>
        
@foreach ($comments as $comment)
        @if ($comment->movie_name == $slot)
        <p class="inline-flex items-center mr-3 text-sm text-white-900 dark:text-white font-semibold">{{$comment->user_name}}</p>
        <p class="text-gray-500 dark:text-white-400">{{ $comment->comment }}</p>
        @endif
@endforeach
<div id="comments-container" class="space-y-2" class=class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
</div>
</section>
<script>
$(document).ready(function () {
    $('#upload-btn').click(function (event) {
        event.preventDefault();
        let comment = $('#comment').val(); // Use jQuery to get the value
        $.ajax({
            url: '{{route("comment.add")}}',
            type: 'POST',
            data: { _token: '{{csrf_token()}}', comment: comment, moviename: '{{$slot}}' },
            success: function(data) {
    // Assuming 'data' contains the new comment object with 'user_name' and 'comment' properties
    var newCommentHtml = '<div class="comment">' +
        '<p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">' + data.user_name + '</p>' +
        '<p class="text-gray-500 dark:text-gray-400">' + data.comment + '</p>' +
        '</div>';

    // Append the new comment HTML to the comments container
    $('#comments-container').append(newCommentHtml);

    // Clear the input field
    $('#comment').val('');
}
        });
    });
});
</script>
</script>
