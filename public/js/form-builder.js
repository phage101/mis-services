function addFormField(
    containerId,
    index,
    label = "",
    type = "text",
    options = "",
    isRequired = false
) {
    const html = `
        <div class="field-row mb-3 p-3 bg-light rounded border position-relative">
            <button type="button" class="btn btn-sm btn-danger position-absolute remove-btn" style="top:-10px; right:-10px;">&times;</button>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Field Label</label>
                    <input type="text" name="form_fields[${index}][label]" class="form-control" value="${label}" placeholder="e.g. Food Preference" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label>Field Type</label>
                    <select name="form_fields[${index}][field_type]" class="form-control field-type-select">
                        <option value="text" ${
                            type === "text" ? "selected" : ""
                        }>Text Input</option>
                        <option value="textarea" ${
                            type === "textarea" ? "selected" : ""
                        }>Text Area</option>
                        <option value="select" ${
                            type === "select" ? "selected" : ""
                        }>Dropdown (Select)</option>
                        <option value="checkbox" ${
                            type === "checkbox" ? "selected" : ""
                        }>Checkbox</option>
                        <option value="radio" ${
                            type === "radio" ? "selected" : ""
                        }>Radio Buttons</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 d-flex align-items-center justify-content-center">
                    <div class="form-check pt-3">
                        <input class="form-check-input" type="checkbox" name="form_fields[${index}][is_required]" id="req_${index}" ${
        isRequired ? "checked" : ""
    }>
                        <label class="form-check-label" for="req_${index}">Required?</label>
                    </div>
                </div>
                <div class="col-12 options-container ${
                    ["select", "radio", "checkbox"].includes(type)
                        ? ""
                        : "d-none"
                }">
                    <label>Options (comma separated)</label>
                    <input type="text" name="form_fields[${index}][options]" class="form-control" value="${options}" placeholder="Option 1, Option 2, Option 3">
                </div>
            </div>
        </div>
    `;
    $(`#${containerId}`).append(html);
}
