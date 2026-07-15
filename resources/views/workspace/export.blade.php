<x-app-layout>
    <x-slot:header>Export Contacts</x-slot:header>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Export Workspace Data</x-ui.card-title>
                    <x-ui.card-description>Download all contacts as a CSV file. Enter the export PIN to proceed.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="space-y-2">
                        <x-ui.label for="pin">Export PIN</x-ui.label>
                        <x-ui.input id="pin" type="password" placeholder="Enter 6-digit PIN" maxlength="6" />
                        @error('pin')
                            <p class="text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="pin-error" class="hidden rounded-md border border-destructive bg-destructive/10 px-3 py-2 text-sm text-destructive">
                        Incorrect PIN. Please try again.
                    </div>

                    <div id="pin-success" class="hidden rounded-md border border-green-500 bg-green-50 px-3 py-2 text-sm text-green-700">
                        PIN verified! You can now download the export.
                    </div>

                    <div class="flex gap-2">
                        <x-ui.button type="button" id="verify-btn">Verify PIN</x-ui.button>
                        <a id="download-btn" href="{{ route('workspace.export-download') }}" class="hidden">
                            <x-ui.button variant="outline" class="bg-green-600 text-white hover:bg-green-700">Download CSV</x-ui.button>
                        </a>
                    </div>

                    <div class="pt-4 border-t text-sm text-muted-foreground">
                        <p><strong>Exported fields:</strong></p>
                        <p>Name, Email, Phone, Phone Country, City, Birthday, Company, Job Title, Website, Address, Status, Rating, Lifecycle Stage, Notes, Comment, Facebook, Twitter, LinkedIn, Group, Tags, Custom Fields, Owner, Created At, Last Contacted</p>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>

    <script>
        document.getElementById('verify-btn').addEventListener('click', async function() {
            const pin = document.getElementById('pin').value;
            const errorDiv = document.getElementById('pin-error');
            const successDiv = document.getElementById('pin-success');
            const downloadBtn = document.getElementById('download-btn');

            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');

            if (!pin) {
                errorDiv.textContent = 'Please enter a PIN.';
                errorDiv.classList.remove('hidden');
                return;
            }

            try {
                const response = await fetch('{{ route("workspace.export-pin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ pin })
                });

                const data = await response.json();

                if (data.verified) {
                    successDiv.classList.remove('hidden');
                    downloadBtn.classList.remove('hidden');
                    this.disabled = true;
                    this.textContent = 'PIN Verified ✓';
                } else {
                    errorDiv.classList.remove('hidden');
                    document.getElementById('pin').value = '';
                    document.getElementById('pin').focus();
                }
            } catch (error) {
                errorDiv.textContent = 'Error verifying PIN. Please try again.';
                errorDiv.classList.remove('hidden');
            }
        });

        document.getElementById('pin').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('verify-btn').click();
            }
        });
    </script>
</x-app-layout>
