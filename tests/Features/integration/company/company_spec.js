describe('Company', function () {
  it('should create a company', function () {
    cy.login()

    cy.createCompany()

    cy.contains('Dunder Mifflin')
    cy.url().should('include', '/dashboard')

    // check if the dashboard contains the company the user is part of
    cy.get('[data-cy=header-menu]').click()
    cy.get('[data-cy=switch-company-button]').click()
    cy.contains('Dunder Mifflin')
  })
})
