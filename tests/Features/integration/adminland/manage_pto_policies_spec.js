describe('Adminland - PTO policies', function () {
  it('should let you manage company PTO policies as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=pto-policies-admin-link]').click()

    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-cancel-button-1]').click()

    // check that we can edit the number of default holidays
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-holidays-1]').clear()
    cy.get('[data-cy=list-edit-input-holidays-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=policy-holidays-1]').contains('100')

    // check that we can edit the number of default sick days
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-sick-1]').clear()
    cy.get('[data-cy=list-edit-input-sick-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=policy-sick-1]').contains('100')

    // check that we can edit the number of default pto days
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-pto-1]').clear()
    cy.get('[data-cy=list-edit-input-pto-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=policy-pto-1]').contains('100')

    // edit the calendar
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=calendar-item-1-1]').click()
    cy.get('[data-cy=list-edit-cta-button-1]').click()

    cy.get('[data-cy=total-worked-days-1]').contains('260')

    cy.hasAuditLog('Updated the company PTO policy for the year', '/1/account/ptopolicies')
  })

  it('should let you manage company PTO policies as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)
    cy.visit('/1/account')
    cy.get('[data-cy=pto-policies-admin-link]').click()

    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-cancel-button-1]').click()

    // check that we can edit the number of default holidays
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-holidays-1]').clear()
    cy.get('[data-cy=list-edit-input-holidays-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=policy-holidays-1]').contains('100')

    // check that we can edit the number of default sick days
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-sick-1]').clear()
    cy.get('[data-cy=list-edit-input-sick-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=policy-sick-1]').contains('100')

    // check that we can edit the number of default pto days
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-pto-1]').clear()
    cy.get('[data-cy=list-edit-input-pto-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=policy-pto-1]').contains('100')

    // edit the calendar
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=calendar-item-1-1]').click()
    cy.get('[data-cy=list-edit-cta-button-1]').click()

    cy.get('[data-cy=total-worked-days-1]').contains('260')
  })
})
