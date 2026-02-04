@props(['idea' => new App\Models\Idea()])

<x-modal name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}" title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}">
    <form x-data="{
        status: @js(old('status', $idea->status->value)),
        newLink: '',
        links: @js(old('links', $idea->links ?? [])),
        newStep: '',
        steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'completed'])))
    }" enctype="multipart/form-data"
        action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}" method="POST">
        @csrf

        @if ($idea->exists)
            @method('PATCH')
        @endif

        <div class="space-y-6">
            <x-form.field label="Title" name="title" placeholder="Enter an idea for your title" required
                :value="$idea->title" />

            <div class="space-y-2">
                <label for="status" class="label">Status</label>

                <div class="flex gap-x-3">
                    @foreach (App\IdeaStatus::cases() as $status)
                        <button data-test="button-status-{{ $status->value }}" type="button"
                            @click="status = @js($status->value)" class="btn flex-1 h-10"
                            :class="{ 'btn-outlined': status !== @js($status->value) }">{{ $status->label() }}</button>
                    @endforeach

                    <input type="hidden" name="status" :value="status" class="input" />
                </div>

                <x-form.error name="status" />
            </div>
            <x-form.field label="Description" name="description" type="textarea" placeholder="Describe your idea..."
                :value="$idea->description" />

            <div class="space-y-2">
                <label for="image" class="label">Featured Image</label>

                @if ($idea->image_path)
                    <div class="space-y-2">
                        <img src="{{ asset(path: 'storage/' . $idea->image_path) }}"
                            class="w-full h-48 object-cover rounded-lg" />
                        <button class="btn btn-outlined h-10 w-full" form="delete-image-form">Remove Image</button>
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/*">
                <x-form.error name="image" />
            </div>
            <div>
                <fieldset class="space-x-3">
                    <legend class="label">Actionable Steps</legend>

                    <template x-for="(step, index) in steps" :key="step.id || index">
                        <div class="flex gap-x-2 items-center">
                            <input :name="`steps[${index}][description]`" x-model="step.description" class="input">
                            <input type="hidden" :name="`steps[${index}][completed]`"
                                x-model="step.completed ? '1' : '0'" class="input">
                            <button type="button" @click="steps.splice(index, 1)" class="form-muted-icon"
                                aria-label="Remove step"><i class="fa fa-times"></i></button>
                        </div>
                    </template>

                    <div class="flex gap-x-2 items-center">
                        <input x-model="newStep" id="new-step" data-test="new-step"
                            placeholder="What needs to be done?" class="input flex-1" spellcheck="false">
                        <button data-test="submit-new-step-button" :disabled="newStep.trim().length === 0"
                            type="button" class="form-muted-icon"
                            @click="steps.push({description: newStep.trim(), completed: false}); newStep = '';"
                            aria-label="Add a new step"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </fieldset>
            </div>

            <div>
                <fieldset class="space-x-3">
                    <legend class="label">Links</legend>

                    <template x-for="(link, index) in links" :key="link">
                        <div class="flex gap-x-2 items-center">
                            <input name="links[]" x-model="link" class="input">
                            <button type="button" @click="links.splice(index, 1)" class="form-muted-icon"
                                aria-label="Remove link"><i class="fa fa-times"></i></button>
                        </div>
                    </template>

                    <div class="flex gap-x-2 items-center">
                        <input x-model="newLink" type="url" id="new-link" data-test="new-link"
                            placeholder="https://example.com" autocomplete="url" class="input flex-1"
                            spellcheck="false">
                        <button data-test="submit-new-link-button" :disabled="newLink.trim().length === 0"
                            type="button" class="form-muted-icon" @click="links.push(newLink.trim()); newLink = '';"
                            aria-label="Add a new link"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </fieldset>
            </div>

            <div class="flex justify-end gap-x-5">
                <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                <button type="submit" class="btn">{{ $idea->exists ? 'Update' : 'Create' }}</button>
            </div>
        </div>
    </form>

    @if ($idea->image_path)
        <form id="delete-image-form"" action="{{ route('idea.image.destroy', $idea) }}" method="POST">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>
