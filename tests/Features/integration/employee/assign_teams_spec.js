describe('Employee - Assign teams', function () {
  it('should assign a team and remove it as administrator', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')

    cy.visit('/1/employees/1')

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-team-modal-blank]').click()
    cy.get('[data-cy=list-team-1]').click()
    cy.get('.existing-teams').contains('product')
    cy.hasAuditLog('Added admin@admin.com to product', '/1/employees/1')
    cy.hasEmployeeLog('Added to the team called product', '/1/employees/1')

    // Open the modal to remove a team and select the first line
    cy.get('[data-cy=open-team-modal]').click()
    cy.get('[data-cy=list-team-1]').click()
    cy.get('.existing-teams').should('not.contain', 'product')
    cy.hasAuditLog('Removed admin@admin.com from product', '/1/employees/1')
    cy.hasEmployeeLog('Removed from the team called product', '/1/employees/1')
  })

  it('should assign a team and remove it as hr', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')

    cy.changePermission(1, 200)
    cy.visit('/1/employees/1')

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-team-modal-blank]').click()
    cy.get('[data-cy=list-team-1]').click()
    cy.get('.existing-teams').contains('product')
    cy.hasEmployeeLog('Added to the team called product', '/1/employees/1')

    // Open the modal to remove a team and select the first line
    cy.get('[data-cy=open-team-modal]').click()
    cy.get('[data-cy=list-team-1]').click()
    cy.get('.existing-teams').should('not.contain', 'product')
    cy.hasEmployeeLog('Removed from the team called product', '/1/employees/1')
  })

  it('should not let a normal user assign teams', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 300)
    cy.visit('/1/employees/1')

    cy.contains('No team set')
    cy.get('[data-cy=open-team-modal-blank]').should('not.exist')
  })

  it('should display a blank state when there is no team in the account', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/employees/1')

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-team-modal-blank]').click()
    cy.get('[data-cy=modal-blank-state-copy]').contains('There is no team in this account yet')
    cy.get('[data-cy=modal-blank-state-cta]').click()
    cy.url().should('include', '/1/account/teams')
  })
})
