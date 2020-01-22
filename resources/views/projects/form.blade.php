@csrf
<div class="field mb-6">
    <label class="label  mb-2 block" for="title">Title</label>

    <div class="control">
        <input type="text"
            class="input bg-transparent border border-grey rounded p-2 text-2xs w-full"
            name="title"
            placeholder="I should start ..."
            value="{{ $project->title }}"
            required>
    </div>
</div>


<div class="field mb-8">
    <label class="label  mb-2 block" for="description">Description</label>

    <div class="control">
        <textarea
            class="textarea bg-transparent border border-grey rounded p-2 text-2xs w-full"
            name="description"
            placeholder="This project is about ..."
            rows="10"
            required>{{ $project->description }}</textarea>
    </div>
</div>

<div class="field">
    <div class="contorl">
        <button class="button is-link mr-2" type="submit">{{ $buttonText }}</button>
        <a href="{{ $project->path() }}">Cancel</a>
    </div>
</div>

@if($errors->any())
    <div class="field mt-6">
        @foreach ($errors->all() as $error)
            <li class="text-sm text-red">{{ $error }}</li>
        @endforeach
    </div>
@endif
