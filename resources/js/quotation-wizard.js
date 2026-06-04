export function registerQuotationWizard(Alpine) {
    Alpine.data('quotationWizard', (config = {}) => ({
        step: 1,
        maxStep: 3,
        jobType: config.oldJobType || '',
        jobTypesRequiringSize: config.jobTypesRequiringSize || [],
        jobTypesRequiringPages: config.jobTypesRequiringPages || [],
        paperRows: config.oldPapers?.length ? config.oldPapers : [{ role: 'cover', type: '', size: '', grammage: '', color: '', notes: '' }],

        requiresSize() {
            return this.jobTypesRequiringSize.includes(this.jobType);
        },

        requiresPages() {
            return this.jobTypesRequiringPages.includes(this.jobType);
        },

        isBooklet() {
            return this.jobType === 'Booklet';
        },

        isBanner() {
            return this.jobType === 'Banner';
        },

        isBusinessCards() {
            return this.jobType === 'Business Cards';
        },

        nextStep() {
            if (this.step < this.maxStep) {
                this.step += 1;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.step > 1) {
                this.step -= 1;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        addPaperRow() {
            this.paperRows.push({ role: 'text', type: '', size: '', grammage: '', color: '', notes: '' });
        },

        removePaperRow(index) {
            if (this.paperRows.length > 1) {
                this.paperRows.splice(index, 1);
            }
        },
    }));
}
