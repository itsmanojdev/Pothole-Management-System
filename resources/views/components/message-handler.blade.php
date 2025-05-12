<script>
    window.addEventListener('load', () => {
        @if (session('success'))
            toast('success', "{{ session('success') }}");
        @elseif (session('error'))
            toast('error', "{{ session('error') }}");
        @elseif (session('info'))
            toast('info', "{{ session('info') }}");
        @endif
    });
</script>
