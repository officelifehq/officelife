describe('Adminland - Expenses', function () {
  it('should let you manage expense categories as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-category-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('travel')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=categories-list]').contains('travel')
    cy.hasAuditLog('Created the expense category called travel', '/1/account/expenses')
  })

  it('should let you manage expense categories as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)

    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-category-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('travel')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=categories-list]').contains('travel')
  })
})
