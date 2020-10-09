describe('Company', function () {
  it('should search an employee and a team', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createTeam('product')

    // Find a team called product
    cy.get('[data-cy=header-find-link]').click()
    cy.get('input[name=search]').type('product')
    cy.get('[data-cy=header-find-submit]').click()

    cy.get('[data-cy=results]').contains('product')

    // Find the current user with email address admin@admin.com
    cy.visit('/1/dashboard')
    cy.get('[data-cy=header-find-link]').click()
    cy.get('input[name=search]').type('admin')
    cy.get('[data-cy=header-find-submit]').click()

    cy.get('[data-cy=results]').contains('admin')
  })
})
