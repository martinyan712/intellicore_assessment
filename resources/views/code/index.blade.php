<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Code') }}
        </h2>
    </x-slot>
    <div class="container text-center"  x-data="{ number:'' }">
        <div class="row">
            <div class="col-4 flex items-center gap-4" >
                <x-input-label for="name" :value="__('Number of codes')"/>
                    <x-text-input id="number" name="number" type="text" class="mt-1 block" :value="old('number')" required autofocus autocomplete="number"  x-model="number"/>
                </div>
                <div class="flex items-center gap-4 col-4">
                    <button x-on:click="generateCode" class="btn btn-success" type="button" name="save">{{ __('Generate') }}</button>
                        <p
                            x-data="{ isGenerated: false }"
                            x-show="isGenerated"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('Created.') }}</p>
                </div>
        </div>
</div>


    <x-widgets.table :url="'/code/list'" :x-ref="'table'">
        <x-slot name="th"> 
            <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Door</th>
            <th>Action</th>
          </tr>
        </x-slot>
        <x-slot name="content">
            <tr>
              <th x-text="event.id"></th>
              <th x-text="event.code"></th>
              <th x-text="(event.doors.length>0)?event.doors[0].name:''"></th>
              <th><a class="btn btn-primary" x-bind:href="'/code/detail/' + event.id">Edit</a></th>
            </tr>
        </x-slot>
    </x-app-widgets.table>
</x-app-layout>

<script>
    async function generateCode(){
        let code =  await(await fetch('/code/generate', {
				method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                },
                body:  JSON.stringify({'number':this.number,'generate':true})
            })).json();
        if(code.success){
            this.isGenerated = true;
            alert("Code Generated");
            location.reload();
        }else{
            if(code?.message?.number){
                alert(code.message.number);
            }else{
                alert(code.message);
            }
        }
    }
			
</script>


