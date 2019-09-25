describe('Adminland - PTO policies', function () {
  it('should let you manage company PTO policies as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=pto-policies-admin-link]').click()

    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-cancel-button-1]').click()
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-name-1]').clear()
    cy.get('[data-cy=list-edit-input-name-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=pto-policies-list]').contains('100')
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
    cy.get('[data-cy=list-edit-button-1]').click()
    cy.get('[data-cy=list-edit-input-name-1]').clear()
    cy.get('[data-cy=list-edit-input-name-1]').type('100')
    cy.get('[data-cy=list-edit-cta-button-1]').click()
    cy.get('[data-cy=pto-policies-list]').contains('100')
  })
})
