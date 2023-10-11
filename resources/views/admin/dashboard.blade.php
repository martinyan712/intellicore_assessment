<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ code_count: {!!$code_count!!},code_used:{!!$code_used!!},generateDisabled:false,digit:'' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>
                        <span style="margin-right:10px;display:none;" x-show="code_count==0">You don't have any code, please click to genenate it. </span> 
                        <span style="display:none;" x-show="code_count>0">You have <span x-text="code_count"></span> code(s), and used <span x-text="code_used"></span> code(s).</span>
                        <button x-on:click="genCodes"class="btn btn-success" x-bind:disabled="generateDisabled" type="button" name="generate_code">Generate Codes</button>
                        <input id="digit" type="text" x-model="digit" />
                        <button x-on:click="checkNumber"class="btn btn-success" x-bind:disabled="generateDisabled" type="button" name="generate_code">Check Code</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    async function genCodes() {
        this.generateDisabled = true;
        let code =  await(await fetch('/code/generate', {
				method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                },
                body:  JSON.stringify({'generate':true})
            })).json();
        this.generateDisabled = false;
        if(code.success){
            this.code_count = code.code_count;
            this.code_used = code.code_used;
        }
    }

    async function checkNumber(){
        let code =  await(await fetch('/code/check', {
				method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                },
                body:  JSON.stringify({'code':this.digit})
            })).json();
        console.log(code);
        alert(code);
    }
			
</script>