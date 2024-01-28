<form action="{{ route('quiz.update', ['id' => $quiz->id]) }}" method="post">
    @csrf
    <label for="title">სათაური:</label>
    <input type="text" id="title" name="title" value="{{ $quiz->title }}" required>
    
    <label for="description">აღწერა:</label>
    <textarea id="description" name="description" required>{{ $quiz->description }}</textarea>
    
    <button type="submit">განახლება</button>
</form>
