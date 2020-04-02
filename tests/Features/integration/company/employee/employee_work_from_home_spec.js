describe('Employee - work from home', function () {
  it('should let the employee indicates that he works from home', function () {
    cy.login()

    cy.createCompany()

    // check that the employee indicates that he doesn't work from home
    cy.visit('/1/employees/1')
    cy.get('[data-cy=work-from-home-today]').should('not.exist')
    cy.get('[data-cy=work-from-home-not-today]').should('exist')
    cy.get('[data-cy=work-from-home-statistics]').should('not.exist')

    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-from-work-home-cta]').check()

    cy.visit('/1/employees/1')
    cy.get('[data-cy=work-from-home-today]').should('exist')
    cy.get('[data-cy=work-from-home-not-today]').should('not.exist')
    cy.get('[data-cy=work-from-home-statistics]').should('exist')
  })
})
