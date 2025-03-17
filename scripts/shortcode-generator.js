(() => {
    const form = document.getElementById("shortcode-generator-form");

    if (!form) {
        return;
    }

    const addOrder = document.getElementById("add-order");

    const generated = document.getElementById('generated');
    const shortcodeDisplay = document.getElementById('shortcode-display');

    addOrder.addEventListener('click', (e) => {
        const button = e.target

        const parent = button.parentElement
        const allSelect = parent.getElementsByTagName('select')
        if (allSelect.length >= 5) {
            return;
        }

        const select = e.target.previousElementSibling

        const newSelect = select.cloneNode(true)
        button.before(newSelect)
    })

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const data = getShortCodeData(new FormData(e.target))

        fetch('/?rest_route=/persona-roles/shortcode/display', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(output => {
                shortcodeDisplay.innerHTML = ""
                shortcodeDisplay.innerHTML = output.data

                generated.innerHTML = ""
                generated.innerHTML = getShortCode(data)
            })

    })
})()


function getShortCodeData(formData) {
    const format = formData.get('format');
    const content = formData.getAll('content').join('').trim()
    const bold = formData.getAll('bold').join('').trim()
    const underlined = formData.getAll('underlined').join('').trim()
    const italic = formData.getAll('italic').join('').trim()
    const filters = {
        parish: formData.get('parish'),
        group: formData.get('group'),
        role: formData.get('role'),
    }
    const order = formData.getAll('order').join('').trim()

    return {format, content, bold, underlined, italic, filters, order}
}

function getShortCode(data)
{
    $sc = `[ups-select format="${data.format}" contenu="${data.content}"`

    if (data.filters.parish && data.filters.parish !== '') {
        $sc += ` paroisse="${data.filters.parish}"`
    }

    if (data.filters.group && data.filters.group !== '') {
        $sc += ` groupe="${data.filters.group}"`
    }

    if (data.filters.role && data.filters.role !== '') {
        $sc += ` role="${data.filters.role}"`
    }

    if (data.bold && data.bold !== '') {
        $sc += ` gras="${data.bold}"`
    }

    if (data.underlined && data.underlined !== '') {
        $sc += ` souligne="${data.underlined}"`
    }

    if (data.italic && data.italic !== '') {
        $sc += ` italique="${data.italic}"`
    }

    if (data.order && data.order !== '') {
        $sc += ` ordre="${data.order}"`
    }

    return $sc + "]";
}
